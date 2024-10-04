<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Grav\Common\Page\Page;


/**
 * ShortcodeSwiffySliderPlugin
 * @category   Extensions
 * @package    Grav\Plugin
 * @subpackage ShortcodeSwiffySliderPlugin
 * @author     Pedro Moreno <https://github.com/pmoreno-rodriguez>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @link       https://github.com/pmoreno-rodriguez/grav-plugin-shortcode-swiffy-slider
 */

class ShortcodeSwiffySliderPlugin extends Plugin
{
    protected $handlers;
    protected $assets;

    private $currentPage = null;

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageContentRaw' => ['onPageContentRaw', 1000],             // before the Shortcode Core plugin
            'onPageContentProcessed' => ['onPageContentProcessed', 1000], // before the Shortcode Core plugin
        ];
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /* Detect which page is being processed, even if it is in a collection.
     * We store it so that our shortcode can use it.
     */
    public function onPageContentRaw(Event $event)
    {
        $this->currentPage = $event['page'];
    }
    public function onPageContentProcessed(Event $event)
    {
        $this->currentPage = $event['page'];
    }
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Initialize configuration
     *
     * @param Event $e
     */
    public function onShortcodeHandlers(Event $e)
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__.'/shortcodes');
    }

}
