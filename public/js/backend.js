const BweinBackendCustomizer = function () {
    this.attributes = {
        headerTitle: 'data-bwein-header-title',
        envTitle: 'data-bwein-env-title',
    };

    this.init = function () {
        let body = document.querySelector('body'),
            header = document.querySelector('#header h1 a'),
            loginForm = null,
            loginFormInner = null;

        if (header === null) {
            loginFormInner = document.querySelector('#main .tl_login_form .formbody');
            if (loginFormInner !== null) {
                loginForm = loginFormInner.parentNode;
                header = document.createElement('div');
                header.className = 'custom-header';
            }
        } else {
            let appTitle = document.querySelector('#header h1 a .app-title');
            if (appTitle === null) {
                appTitle = document.createElement('span');
                appTitle.className = 'app-title';
                appTitle.innerHTML = header.innerHTML;
                header.innerHTML = appTitle.outerHTML;
            }
        }

        if (header === null) {
            return;
        }

        // Header title
        const headerTitle = body.getAttribute(this.attributes.headerTitle);
        if (typeof headerTitle !== undefined && headerTitle?.length > 0) {
            const titleContainer = document.createElement('span');
            titleContainer.className = 'custom-title';
            titleContainer.innerHTML = headerTitle;
            header.appendChild(titleContainer);

            if (loginForm !== null && loginFormInner !== null) {
                loginForm.insertBefore(header, loginFormInner);
            }
        }

        // Env title
        const envTitle = body.getAttribute(this.attributes.envTitle);
        if (typeof envTitle !== undefined && envTitle?.length > 0) {
            const envContainer = document.createElement('span');
            envContainer.className = 'env-title';
            envContainer.innerHTML = envTitle;
            if (loginForm !== null && loginFormInner !== null) {
                const loginFormHeadline = document.querySelector('#main .tl_login_form .formbody h1');
                if (loginFormHeadline !== null) {
                    loginFormInner.insertBefore(envContainer, loginFormHeadline);
                }
            } else {
                header.appendChild(envContainer);
            }
        }
    };
};

const bwein_backend_customizer = new BweinBackendCustomizer();
document.addEventListener("DOMContentLoaded", function () {
    bwein_backend_customizer.init();
});
