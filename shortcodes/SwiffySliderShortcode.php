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
          
            // Autoplay
            $swiffy_autoplay = $shortcode->getParameter('autoplay', 'false');
            $swiffy_autoplayPauseOnHover = $shortcode->getParameter('autoplayPauseOnHover', 'true');
            $swiffy_autoplayTimeout = $shortcode->getParameter('autoplayTimeout', '4000');

            //Animation options
            $swiffy_animationThreshold = $shortcode->getParameter('animationTreshold','0.3');

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

                // get title attribute and clean it
                preg_match('/title="(.*?)"/', $image, $desc);
                $title_clean = null;

                // If a title exists, process it
                if (!empty($desc)) {
                    // replace <br> tags with " | "
                    $title_clean = preg_replace('/<br *\/*>/', '. ', html_entity_decode($desc[1]));
                    // strip html
                    $title_clean = strip_tags(html_entity_decode($title_clean));
                    // set as new title
                    $image = preg_replace('/title=".*?"/', "title=\"$title_clean\"", $image);
                } else {
                    // If no title, ensure title_clean is null
                    $title_clean = null;
                }

                // Combine and push the image data into final array
                array_push($images_final, [
                    "image" => $image,
                    "src" => $links[1],
                    "alt" => $alts[1],
                    "title" => $title_clean,
                ]);
            }

            $classes = $shortcode->getParameter('classes', '');

            // Transform classes, add "slider-" if they don't already have it
            $classes_array = explode(' ', $classes);
            $prefixed_classes = array_map(function($class) {
                return (strpos($class, 'slider-') === 0) ? $class : 'slider-' . $class;
            }, $classes_array);

            // Join the classes back into a string
            $classes = implode(' ', $prefixed_classes);

            
            return $this->twig->processTemplate(
                'partials/swiffy-slider.html.twig',
                [
                    'page' => $this->grav['page'], // used for image resizing
                    'swiffy_id' => $id,
                    
                    // carousel settings
                    'swiffy_autoplay' => filter_var($swiffy_autoplay, FILTER_VALIDATE_BOOLEAN),
                    'swiffy_autoplayPauseOnHover' => filter_var($swiffy_autoplayPauseOnHover, FILTER_VALIDATE_BOOLEAN),
                    'swiffy_autoplayTimeout' => $swiffy_autoplayTimeout,
                    'swiffy_animationThreshold' => $swiffy_animationThreshold,
                    'classes' => $classes,
                    
                    // images
                    'images' => $images_final,
                ]
            );
        });

    }
}