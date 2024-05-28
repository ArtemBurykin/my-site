const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addStyleEntry('reset-password', './assets/security/reset-password/styles/reset-password.scss')
    .addStyleEntry('our-cases-page', './assets/site/our-cases-page/our-cases.page.scss')
    .addStyleEntry('blog-page', './assets/site/blog-page/blog.page.scss')
    .addStyleEntry('case-page', './assets/site/case-page/case.page.scss')
    .addStyleEntry('post-page', './assets/site/post-page/post.page.scss')
    .addEntry('index-page', './assets/site/index-page/index.page.js')
    .addEntry('contacts-page', './assets/site/contacts-page/contacts.page.js')
    .addEntry('common', './assets/site/common.js')
    .addEntry('admin', './assets/admin/admin.js')
    .enableSassLoader()

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
    .enableSassLoader()
    .copyFiles({
        from: './assets/common/images',
        to: 'images/[path][name].[hash:8].[ext]',
    })
;

module.exports = Encore.getWebpackConfig();
