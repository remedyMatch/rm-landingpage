var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app_js', './assets/app.js')
    .addStyleEntry('app_css', './assets/app.css')

    .addEntry('admin_js', './assets/admin.js')
    .addStyleEntry('admin_css', './assets/admin.css')

    .addStyleEntry('login_css', './assets/css/login.css')

    .copyFiles({
        from: './node_modules/language-icons/icons',
        to: './icons/[path][name].[ext]'
    })

    .splitEntryChunks()

    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
;

module.exports = Encore.getWebpackConfig();
