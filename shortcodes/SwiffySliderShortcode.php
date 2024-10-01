<?php

namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

/**
 * ShortcodeSwiffySliderPlugin
 * @category   Extensions
 * @package    Grav\Plugin
 * @subpackage ShortcodeSwiffySliderPlugin
 * @author     Pedro Moreno <https://github.com/pmoreno-rodriguez>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @link       https://github.com/pmoreno-rodriguez/grav-plugin-shortcode-swiffy-slider
 */

class SwiffySliderShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('swiffy-slider', function(ShortcodeInterface $shortcode) {

            $id = 'swiffy-' . $this->shortcode->getId($shortcode);

            // get default settings
            $pluginConfig = $this->config->get('plugins.shortcode-swiffy-slider');

            // get the current page in process (i.e. the page where the shortcode is being processed)
            // warning, it can be different from $this->grav['page'], if ever we browse a collection
            $currentPage = $this->grav['plugins']->getPlugin('shortcode-swiffy-slider')->getCurrentPage();

            // check if the rendered page will be cached or not
            $renderingCacheDisabled = !is_null($currentPage)
                && isset($currentPage->header()->cache_enable)
                && !$currentPage->header()->cache_enable
                || !$this->grav['config']->get('system.cache.enabled');
            
                // we also check that the page will not be cached once rendered (otherwise the carousel will not be generated on the normal page)
            if ( $renderingCacheDisabled &&  isset($this->grav['page']->header()->content))  // if the current page does not cache its rendering and the current page has a collection
                    {
                    return $shortcode->getContent(); // return unprocessed content (because in RSS, Javascripts don't work)
                    }

            /**** ASSETS ****/

            $compress = $this->config->get('plugins.shortcode-swiffy-slider.production-mode', true);
            $min = $compress ? '.min' : '';
            
            // Load extensions
            if ($this->config->get('plugins.shortcode-swiffy-slider.enable_extensions', true)) {
                $this->shortcode->addAssets('js', ['plugin://shortcode-swiffy-slider/js/swiffy-slider-extensions' . $min . '.js', [ 'group' => 'bottom' ] ]);
            }

            // Assets
            $this->shortcode->addAssets('js', ['plugin://shortcode-swiffy-slider/assets/js/swiffy-slider' . $min . '.js', [ 'group' => 'bottom' ] ]);
            
            $this->shortcode->addAssets('css', 'plugin://shortcode-swiffy-slider/assets/css/swiffy-slider' . $min . '.css');

            $custom_css = $this->config->get('plugins.shortcode-swiffy-slider.custom_css');

            if ($custom_css) {
                $this->grav['assets']->addInlineCss($custom_css);
            }

            
            // overwrite default slider settings, if set by user
            $swiffy_removeTitle = $shortcode->getParameter('removeTitle', $pluginConfig['slider']['removeTitle']);


            // Autoplay
            if ($shortcode->getParameter('autoplay', $pluginConfig['slider']['autoplay'], true)) {
                $swiffy_autoplay = 'slider-nav-autoplay';
                $swiffy_autoplayTimeout = ($shortcode->getParameter('autoplayTimeout', $pluginConfig['slider']['autoplayTimeout']));
                $swiffy_autoplayPauseOnHover = ($shortcode->getParameter('autoplayPauseOnHover', $pluginConfig['slider']['autoplayPauseOnHover'], true)) ? 'slider-nav-autopause' : '';
            } else {
                $swiffy_autoplay = '';
                $swiffy_autoplayTimeout = '';
            }


            /**** LOAD CONTENT ****/

            // find all images, that a carousel contains
            $content = $shortcode->getContent();

            // remove <p> tags
            $content = preg_replace('(<p>|</p>)', '', $content);
            // split up images to arrays of img links
            preg_match_all('|<img.*?>|', $content, $images);
            
            $images_final = [];
            foreach ($images[0] as $image) {
                // get src attribute
                preg_match('|src="(.*?)"|', $image, $links);

                // get alt attribute
                preg_match('|alt="(.*?)"|', $image, $alts);

                // get title attribute - and strip html from it
                // e.g.:    "<strong>Title 1</strong><br />Example 1<br/>More description<br>Bla bla"
                // becomes: "Title 1 | Example 1 | More description | Bla bla"
                preg_match('/title="(.*?)"/', $image);
                $title_clean = null;
                if (!empty($desc)) {
                    if (!filter_var($swiffy_removeTitle, FILTER_VALIDATE_BOOLEAN)) {
                        // replace br tags with " | "
                        $title_clean = preg_replace('/<br *\/*>/', ' | ', html_entity_decode($desc[1]));
                        // strip html
                        $title_clean = strip_tags(html_entity_decode($title_clean));
                        // set as new title
                        $image = preg_replace('/title=".*?"/', "title=\"$title_clean\"", $image);
                    } else {
                        $image = preg_replace('/title=".*?" /', "", $image);
                    }
                } else {
                    $desc[1] = null;
                }

                // combine
                array_push($images_final, [
                    // full
                    "image" => $image,
                    "src" => $links[1],
                    "alt" => $alts[1],
                    "title" => $title_clean,
                    ]);
            }

            $classes = $shortcode->getParameter('classes', '');
            
            return $this->twig->processTemplate(
                'partials/swiffy-slider.html.twig',
                [
                    'page' => $this->grav['page'], // used for image resizing
                    'swiffy_id' => $id,
                    
                    // carousel settings
                    'swiffy_autoplay' => $swiffy_autoplay,
                    'swiffy_autoplayTimeout' => $swiffy_autoplayTimeout,
                    'swiffy_autoplayPauseOnHover' => isset($swiffy_autoplayPauseOnHover),
                    'classes' => $classes,
                    
                    // images
                    'images' => $images_final,
                ]
            );
        });

    }
}