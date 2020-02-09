[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/francis94c/vue-ci/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/francis94c/vue-ci/?branch=master) [![Maintainability](https://api.codeclimate.com/v1/badges/4a22678ec8ceee6cad6e/maintainability)](https://codeclimate.com/github/francis94c/vue-ci/maintainability)

# vue-ci

<p style="text-align:center;">
<img width="100" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/vue/vue.png"/>
<img width="100" src="https://cdn.worldvectorlogo.com/logos/codeigniter.svg"/>
</p>

WebPack for Vue in Code Igniter

__Disclaimer:__ As there are different ways to use Vue, this library implements a different usage of Vue via CDN.

### Installation ###
Download and Install Splint from https://splint.cynobit.com/downloads/splint and run the below from the root of your Code Igniter project.
```bash
splint install francis94c/vue-ci
```

### Usage ###
Basically, When building Single Page Applications (SPAs), You tend to load certain JavaScript files on the client side. with this library, you can group scripts to load under a URL.

#### Step 1 ####
Create a config file `vue.php` under `applications/config/vue.php`. This config file will have content similar to the following. Which we'll explain below.
```php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['vue'] = [
  'groups' => [
    'main' => [
      'components' => [
        'vue/components/login-screen',
        'vue/components/signup-screen'
      ],
      'scripts' => [
        'vue/auth_app'
      ]
    ],
    'initState' => [
      'scripts' => [
        'vue/vuex/mutate/init_state'
      ]
    ]
  ]
];
```
