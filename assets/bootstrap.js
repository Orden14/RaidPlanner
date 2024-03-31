import { startStimulusApp } from '@symfony/stimulus-bridge'

import $ from 'jquery'
global.$ = global.jQuery = $
global.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js')
require('jquery-confirm')
require('jquery-confirm/css/jquery-confirm.css')

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
))
