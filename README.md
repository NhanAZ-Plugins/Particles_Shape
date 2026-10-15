# Particles_Shape

Particles_Shape creates crown, cloud, helix, double-helix and laser particle effects. The plugin is authored and maintained by NhanAZ.

## Use

Operators can add an effect at their current position, list active effects and remove an effect:

```text
/particles add <helix|crown|cloud|dhelix|laser> <name>
/particles list
/particles remove <name>
```

Each server can run up to five effects at once. Each scheduled run emits no more than 100 particle points. Fixed effects stop if their world unloads; laser effects stop when their player disconnects or dies. All scheduled effects are cancelled when the plugin is disabled. Effect names are limited to 32 lowercase letters, digits, hyphens or underscores and cannot be reused while active.

## Compatibility and verification

The manifest declares Axolotl-PM API 5.0.0 as the minimum API. PHPStan and PHAR builds use pinned API 5.0.0 and 5.49.1 source revisions; the isolated server startup check uses Axolotl-PM 5.49.1. Automated geometry fixtures verify particle counts and coordinate bounds. These checks do not claim connected-client visual verification.

The build workflow uses [DevTools](https://github.com/NhanAZ/DevTools) to analyze the source, build the plugin PHAR and verify its contents. The source is distributed under the [MIT License](LICENSE).
