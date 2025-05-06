import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Staff/**/*.php',
        './resources/views/filament/staff/**/*.blade.php',
        './resources/views/vendor/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
