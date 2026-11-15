// Builds the minified assets: sass + Lightning CSS for styles, esbuild for scripts.
// Pass --watch to rebuild on changes. Watching polls the files because file system
// events of Windows hosts don't reach the Docker containers.
import { statSync, unwatchFile, watchFile, writeFileSync } from 'node:fs';
import { relative } from 'node:path';
import { fileURLToPath } from 'node:url';
import browserslist from 'browserslist';
import { build } from 'esbuild';
import { browserslistToTargets, transform } from 'lightningcss';
import { compileAsync } from 'sass-embedded';

const assets = [
    { src: 'public/css/backend.scss', dest: 'public/css/backend.min.css' },
    { src: 'public/js/backend.js', dest: 'public/js/backend.min.js' },
];

const targets = browserslistToTargets(browserslist());

// Each builder writes the destination file and returns the source files it depends on
const builders = {
    async scss({ src, dest }) {
        const result = await compileAsync(src);
        const { code, warnings } = transform({ filename: dest, code: Buffer.from(result.css), minify: true, targets });

        for (const warning of warnings) {
            console.warn(`  warning: ${warning.message} (${src})`);
        }

        writeFileSync(dest, code);

        return result.loadedUrls.filter((url) => url.protocol === 'file:').map((url) => relative('.', fileURLToPath(url)));
    },

    async js({ src, dest }) {
        const result = await build({ entryPoints: [src], outfile: dest, minify: true, metafile: true, logLevel: 'silent' });

        return Object.keys(result.metafile.inputs);
    },
};

const watching = process.argv.includes('--watch');

async function buildAsset(asset) {
    const start = performance.now();
    const prefix = watching ? `[${new Date().toLocaleTimeString('de-DE')}] ` : '';

    try {
        const inputs = await builders[asset.src.split('.').pop()](asset);
        const size = (statSync(asset.dest).size / 1024).toFixed(1);
        const time = Math.round(performance.now() - start);

        console.log(`${prefix}✔ ${asset.dest.padEnd(36)} ${size.padStart(6)} kB  ${time} ms`);

        return inputs;
    } catch (error) {
        console.error(`${prefix}✖ ${asset.src}\n${error.message}`);
        process.exitCode = 1;

        return null;
    }
}

function watchAsset(asset, inputs) {
    let watched = [];

    const update = (files) => {
        // Keep watching the previous files if the build failed
        if (!files) {
            return;
        }

        watched.forEach((file) => unwatchFile(file, rebuild));
        watched = files;
        watched.forEach((file) => watchFile(file, { interval: 300 }, rebuild));
    };

    async function rebuild(current, previous) {
        if (current.mtimeMs !== previous.mtimeMs) {
            update(await buildAsset(asset));
        }
    }

    update(inputs ?? [asset.src]);
}

// Build one after another to keep the output order stable
const results = [];

for (const asset of assets) {
    results.push(await buildAsset(asset));
}

if (watching) {
    process.exitCode = 0;
    assets.forEach((asset, i) => watchAsset(asset, results[i]));
    console.log('Watching for changes, press Ctrl+C to stop');
}
