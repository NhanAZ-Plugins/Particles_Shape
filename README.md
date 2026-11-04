# Particles_Shape

Particle shape effects for Axolotl-PM API 5, authored by NhanAZ and licensed under MIT

[![CI](https://github.com/NhanAZ-Plugins/Particles_Shape/actions/workflows/build.yml/badge.svg)](https://github.com/NhanAZ-Plugins/Particles_Shape/actions/workflows/build.yml)
[![Latest release](https://img.shields.io/github/v/release/NhanAZ-Plugins/Particles_Shape)](https://github.com/NhanAZ-Plugins/Particles_Shape/releases/latest)
[![License](https://img.shields.io/github/license/NhanAZ-Plugins/Particles_Shape)](https://github.com/NhanAZ-Plugins/Particles_Shape/blob/main/LICENSE)
[![Discord](https://img.shields.io/discord/986553214889517088?label=Discord&logo=discord&logoColor=white&color=5865F2)](https://discord.gg/j2X83ujT6c)

## Overview

This plugin targets the PocketMine-MP and Axolotl-PM API 5 ecosystem and is maintained under [NhanAZ-Plugins](https://github.com/NhanAZ-Plugins).

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
