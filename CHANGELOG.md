# [1.1.0](https://github.com/kanojo-db/kanojo/compare/v1.0.2...v1.1.0) (2023-09-24)


### Bug Fixes

* **api:** untranslated age string ([ed7deca](https://github.com/kanojo-db/kanojo/commit/ed7decaff2c82067f2bbd67b6376841c1fb7d93c))
* **api:** use movie versions for movie info ([f188bdf](https://github.com/kanojo-db/kanojo/commit/f188bdfdf92a589e1a99179196f2fbd3ea41e792))
* **events:** non-existing event dispatched for age updates ([abb2d62](https://github.com/kanojo-db/kanojo/commit/abb2d62aaceb16c1f58056e72801a551b14b04ad))
* **events:** register AgeUpdateSubscriber ([61ad804](https://github.com/kanojo-db/kanojo/commit/61ad8047fd8c63ec7a0c1df92d22b88f1a697a7a))
* **history:** audits not showing ([5b28770](https://github.com/kanojo-db/kanojo/commit/5b28770670380c53b45ca252c52d102ba20c3c1f))
* **i18n:** update translations for French ([1266f9c](https://github.com/kanojo-db/kanojo/commit/1266f9c6c60a48095763cc74f743797136ae71a1))
* **media:** bad route for media deletion ([9943878](https://github.com/kanojo-db/kanojo/commit/99438783a415ae6581c31268989f15d2ce357b28))
* **models:** do not track history for system columns ([fd610e0](https://github.com/kanojo-db/kanojo/commit/fd610e022ca775d5bcd12f757a85d965749d7124))
* **movie:** make version release date nullable ([c14bddc](https://github.com/kanojo-db/kanojo/commit/c14bddcbb86357e005c65d94735b2603910d9e0c))
* remove IP and user agent from change histories ([1333e50](https://github.com/kanojo-db/kanojo/commit/1333e50edbce8196f3974ab427aedcadd50bf39e))
* revert vuetify treeshaking ([9b53b3d](https://github.com/kanojo-db/kanojo/commit/9b53b3d08525450bf6ba4f04db4740b190491a9c))
* **search:** navigation loop on media pages due to type parameter ([d1c0e0e](https://github.com/kanojo-db/kanojo/commit/d1c0e0e50468b90700c8c8ee1c9c68cba4b2a8b9))
* **user:** do not redirect logged in user on collection routes ([f273d52](https://github.com/kanojo-db/kanojo/commit/f273d5213eb64cdcd47f6bc3c629a95d766aa32e))


### Features

* **console:** add slug update command for single record ([0eb3e6b](https://github.com/kanojo-db/kanojo/commit/0eb3e6b0a2433d2b65eace54f56c392863ab1ba4))
* **item:** generate social images automatically ([#559](https://github.com/kanojo-db/kanojo/issues/559)) ([b14537a](https://github.com/kanojo-db/kanojo/commit/b14537a64a596bb445cc74b9aff642b68f5eecfe))
* **user:** ability to delete user account ([#561](https://github.com/kanojo-db/kanojo/issues/561)) ([4967f77](https://github.com/kanojo-db/kanojo/commit/4967f7703a8a9340405a0e3be7e2c5a89a9823b7))

## [1.0.2](https://github.com/kanojo-db/kanojo/compare/v1.0.1...v1.0.2) (2023-09-13)

### Bug Fixes

-   **person:** allow mass update for gender_id field ([5e2c051](https://github.com/kanojo-db/kanojo/commit/5e2c05113ef985c16ad6c79af1ebaf9ed573037a))
-   **person:** remove extra database query on update ([ae95847](https://github.com/kanojo-db/kanojo/commit/ae95847367efb3234dac71c03d7bc3922b6cf6a9))
-   **user:** bad permission check on collection controllers ([b2ea442](https://github.com/kanojo-db/kanojo/commit/b2ea442a0e382f402a40c429a4a35ab85c4f316a))
-   **vuetify:** do not bundle all components ([4d8224f](https://github.com/kanojo-db/kanojo/commit/4d8224fa6b5d0edd1a6b365a16d0d165e2eb923c))

## [1.0.1](https://github.com/kanojo-db/kanojo/compare/v1.0.0...v1.0.1) (2023-09-12)

### Bug Fixes

-   add OpenGraph image size ([51314b8](https://github.com/kanojo-db/kanojo/commit/51314b82ad7e16f2cba0a6fd410592b3abcf1dd0))
-   add v-app to social media previews ([f5e0a5b](https://github.com/kanojo-db/kanojo/commit/f5e0a5b72529de95f8962fe071228692455e65a7))
-   attempt to properly handle social URLs in SSR ([3f819dd](https://github.com/kanojo-db/kanojo/commit/3f819dd130dcff78c6b878f94d3120c4682373bf))
-   disable social login routes ([9e35d68](https://github.com/kanojo-db/kanojo/commit/9e35d68633d73f384bb49cb74a78f4fda93e1eef))
-   do not log visits from crawlers ([93d5006](https://github.com/kanojo-db/kanojo/commit/93d50061cf4f4e128ff017707afaa4dc8b4663cc))
-   escape schedule commands ([f691ddf](https://github.com/kanojo-db/kanojo/commit/f691ddf537687a3211a65565a40197de35518980))
-   force body to full height on previews ([8051855](https://github.com/kanojo-db/kanojo/commit/8051855f8ec9126a9784b3bb98ba1f9ea44c2a06))
-   force preview elements to full height ([2b351c3](https://github.com/kanojo-db/kanojo/commit/2b351c33420e8745bf205c4f4aae14fdf3d1bd0f))
-   ignore staff when tracking visits ([396c671](https://github.com/kanojo-db/kanojo/commit/396c671e623e69be7779dc10f76c4b88fceeae81))
-   issues with developer routes ([fc06367](https://github.com/kanojo-db/kanojo/commit/fc06367f2d842d9f4722cbc10803969f33262be5))
-   opengraph image URL ([3d610f4](https://github.com/kanojo-db/kanojo/commit/3d610f4d24ecfa07d6daeabeb11ec12a067f1f94))
-   proper spacing on media preview ([41f1dc1](https://github.com/kanojo-db/kanojo/commit/41f1dc134456e0c170e5a04f9c3cdf3b192ac1fc))
-   remove body margins on previews ([cc54301](https://github.com/kanojo-db/kanojo/commit/cc54301313cefdef39f58439bb1d739540850683))
-   remove strict size for preview pages ([c23c0d6](https://github.com/kanojo-db/kanojo/commit/c23c0d64070552e557ae1128be28977b32ad714e))
-   remove unscoped CSS ([4039ba4](https://github.com/kanojo-db/kanojo/commit/4039ba4c71ed3d398103498a1c14c53087e0a852))
-   **ssr:** undefined variable in site name ([ba19c7d](https://github.com/kanojo-db/kanojo/commit/ba19c7d194e879dc05b69bce73aa173dcfff1e5f))
-   use absolute URL for preview ([d01e0c3](https://github.com/kanojo-db/kanojo/commit/d01e0c323c3f16d2689e1e4417a0ff02b3f7e173))
-   use full height for all elements on previews ([993abe0](https://github.com/kanojo-db/kanojo/commit/993abe0afc6567347536a10c688760a744c2d646))
-   use hardcoded URL for preview ([457df64](https://github.com/kanojo-db/kanojo/commit/457df64ede057375c9f2ef7c74c8ecb2cb031d74))

## 1.0.0 (2023-09-11)

Initial release
