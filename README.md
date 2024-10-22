# Grav Swiffy Slider Plugin

![](assets/swiffy-slider.jpg)

## About

The **Shortcode Swiffy-Slider** plugin makes it easy to create [Swiffy Slider](https://swiffyslider.com/) based sliders from your page content. Swiffy Slider provides a wide range of features and functionality that can be accessed directly through the Grav admin panel. Features include:

* Navigate with Touch, Keyboard, trackpad, pen and Mouse - because it is just browser scrolling
* Uses native browser scroll behavior, scroll-snap and CSS grid to provide the best mobile and touch experience
* Can run in CSS only mode - no js for even better performance
* SEO friendly - all content is in pure markup
* WCAG friendly - all content is in pure markup and can be annotated accordingly, supports tabbing, keyboard navigation, aria attributing and all what is needed.
* Setup is done in pure markup and css classes, no scripting required
* No js loading of slides, configuration or initialization
* Vanilla javascript, less than 1.3kb ~110 lines
* Very low overall footprint ~3.5kb in total (css+js gzip'ed)

## Installation

Typically a plugin should be installed via [GPM](http://learn.getgrav.org/advanced/grav-gpm) (Grav Package Manager):

```
$ bin/gpm install shortcode-swiffy-slider
```

Alternatively it can be installed via the [Admin Plugin](http://learn.getgrav.org/admin-panel/plugins)


## Basic Example

This is a basic example that shows off how you can easily turn 3 images into a slider:

```
[swiffy-slider autoplay=true autoplayTimeout=3000 autoplayPauseOnHover=true classes="slider-item-reveal slider-nav-round slider-item-ratio slider-item-ratio-21x9"]
![pic01](pic01.jpg "Image 1<br />This is the first image<br />Additional info")
![pic02](pic02.jpg "pic02")
![pic03](pic03.jpg "pic03")
![pic04](pic04.jpg "pic04")
![pic05](pic05.jpg "pic05")
![pic06](pic06.jpg "pic06")
[/swiffy-slider]
```

In this examaple, we are wrapping 3 markdown-syntax images with the `[swiffy-slider][/swiffy-slider]` shortcode tag. 

## Swiffy Slider Options

Options can be passed to Swiffy Slider as attributes of the shortcode. All attributes in the shortcode are enclosed in quotes, as you can see in the example above.

The options available are fully documented on the Swiffy Slider site: [https://swiffyslider.com/docs/](https://swiffyslider.com/docs/), but a summary of the current ones appears below:

## CSS classes and options

### Slider options

For the `swiffy-slider` wrapper. The `item-*` option classes affect the slides (The `slider-container` children).

| CSS class | Description |
|-----------|-------------|
| `item-show2`<br> `item-show3`<br> `item-show4`<br> `item-show5`<br> `item-show6` | Shows 2, 3, 4, 5 or 6 slides at a time in the slider wrapper. Each slide is either 1/2, 1/3, 1/4, 1/5 or 1/6 of the slider wrapper width. |
| `item-show2-sm` | Shows 2 slides at a time in the slider wrapper when in small viewport. By default show2-5 will show only one slide when in viewports less than 62rem (992px in most cases). With this option it shows 2 in small viewports. |
| `item-reveal` | Reveals some of the previous and next slide. Each slide is either 1/1, 1/2, 1/3, 1/4 or 1/6 of the slider wrapper width minus a little to reveal the next and previous slides. |
| `item-ratio` | Enables ratio sizing of the slide elements. Default ratio is 2:1 or 50%, meaning the slides have half the height of their width. This option sets `object-fit:cover;` on the first element inside the slide element to stretch images to fill out the slide box and keep aspect ratio. |
| `item-ratio-32x9`<br> `item-ratio-21x9`<br> `item-ratio-2x1`<br> `item-ratio-16x9`<br> `item-ratio-4x3`<br> `item-ratio-1x1`<br> `item-ratio-3x4` | Controls the slide ratio when ratio is enabled. Default ratio is 2:1 or 50%, meaning the slides have half the height of their width. |
| `item-ratio-contain` | Sets the content of a ratio-enabled slide to have `object-fit:contain;` instead of the default `object-fit:cover;`. This ensures that if the content of the slide is an image or embedded video, it is scaled down so all is visible within the slide box. |
| `item-nogap` | Removes the horizontal gap between slides. |
| `item-snapstart` | Snaps slides to the start of the slider wrapper instead of the center when using `item-reveal`. |
| `item-nosnap` | Removes auto-snapping for slides, allowing them to slide freely. This primarily affects touch devices as navigating with arrows and indicators is per slide or per page. |
| `item-nosnap-touch` | Same effect as `item-nosnap` but only on devices that have a primary input which is not a mouse, i.e. mobile `media (hover: none)`. |
| `item-first-visible` | Use with `nav-autohide` to hide the previous navigation arrow when the slider loads. Will automatically be removed or added when the first slide is not or is visible. |
| `item-last-visible` | Use with `nav-autohide` to hide the next navigation arrow when the slider loads. Will automatically be removed or added when the last slide is not or is visible. |
| `item-helper` | For debugging: Adds a test layout to slide items; minimum height, background color, centers content, and background. Meant for testing and should be removed in real code. |

### Navigation options

For the `swiffy-slider` wrapper. The `nav-*` option classes affect the `nav` elements.

| CSS class | Description |
|-----------|-------------|
| `nav-page` | Slides the entire page when showing more than one slide item on the slider wrapper. Default behavior moves just one slide to the left or right. |
| `nav-noloop` | Disables slider loop - so when on first/last slide, navigating previous/next does not take the user to the last/first slide. |
| `nav-nodelay` | Disables smooth scrolling when sliding using navigation buttons, indicators, and autoplay. Makes the new slide or page appear instantly with no scroll smoothing. Does not affect touch navigation. |
| `autoplay` | Automatically slides to the next slide or next page in intervals. Default is 2500 ms = 2.5s. It can be `true` or `false` |
| `autoplayTimeout` attribute | Changes the default autoplay interval - value is in ms. `data-nav-autoplay-interval="3000"`. Default value is 2500, minimum value is 750 ms. |
| `autoplayPauseOnHover` | Stops and restarts autoplay when the mouse is hovering over the slider wrapper or when it is touched on touch devices. Will restart on mouseout, but not when touch ends. It can be `true` or `false`|
| `nav-round`<br> `nav-square`<br> `nav-arrow`<br> `nav-chevron`<br> `nav-caret`<br> `nav-caretfill` | Changes the default navigation chevrons to an alternative navigation style using different arrows. |
| `nav-touch` | Shows navigation buttons on touch devices. By default, navigation buttons are hidden on touch devices using the `media (hover: none)` query. Adding this option makes the navigation buttons always visible on touch devices. |
| `nav-visible` | Makes the nav buttons visible always. By default, navigation buttons are hidden until the slider wrapper is hovered. |
| `nav-outside` | Moves the navigation buttons outside the slider wrapper and shrinks the width of the slider wrapper accordingly (by 3 or 5 rem on each side depending on navigation style). |
| `nav-outside-expand` | Moves the navigation buttons outside the slider wrapper by applying negative margins (-3 or -5 rem) so the slides and wrapper keep their size. The navigation buttons overlay surrounding content. |
| `nav-scrollbar` | Makes the scrollbar for the `container` visible. Acts as an indicator and navigation if running in CSS-only mode. On touch devices, the scrollbar is not shown when not sliding because that is how the browser behaves. |
| `nav-dark` | Changes the navigation buttons to a dark version. Black arrows or a black circle with white arrows. |
| `nav-autohide` | Hides the appropriate navigation arrow when the first or last slide is visible to indicate that sliding is at its start or end. On load, the arrow will first disappear when the script is loaded. Add `item-first-visible` to the `swiffy-slider` instance together with `nav-autohide` to hide the start arrow on load before JS executes. |

### Indicator options

For the `swiffy-slider` wrapper. The `indicators-*` option classes affect the `indicators` child elements.

| CSS class | Description |
|-----------|-------------|
| `indicators-round` | Changes the default indicators to a circle. |
| `indicators-square` | Changes the default indicators to a square. |
| `indicators-outside` | Moves the indicator buttons under the slider wrapper and increases the height of the slider wrapper but not the slides themselves. |
| `indicators-dark` | Changes the indicator buttons to a dark version. |
| `indicators-highlight` | Highlights the active indicator even more by increasing its size. |
| `indicators-sm` | Shows indicator buttons on small devices under 992px in width. By default, indicator buttons are hidden on small devices. Adding this option makes the indicator buttons always visible. Since the number of indicators and the number of slides do not match on small devices when showing more than one item per page, do not use this option in that case. |

### Animation options

For the `swiffy-slider` wrapper. The `nav-animation-*` option classes affect the animation of slides when they slide into view.

| CSS class | Description |
|-----------|-------------|
| `nav-animation` | Enables animation on slides. An animation effect class is also required for animation to be enabled. |
| `nav-animation-appear` | Appear animation using opacity and scale, starting from 30% opacity and a 90% scale. |
| `nav-animation-fadein` | Fade-in animation using opacity, starting from 50% opacity. Can be combined with `nav-animation-scale/scaleup`. |
| `nav-animation-scale` | Scale-up animation using scale, starting with 90% size. Can be combined with `nav-animation-fadein`. |
| `nav-animation-scaleup` | Scale-up animation using scale, starting with 25% size. Can be combined with `nav-animation-fadein`. |
| `nav-animation-turn` | Turn animation using rotateY, starting with 70deg rotation. |
| `nav-animation-slideup` | Slide-up animation using translateY, starting at 60% of the height. |
| `animationTreshold` attribute | Changes the default animation threshold - value is between 0-1. `data-slider-nav-animation-threshold="0.3"`. Default value is 0.3. This setting defines how much of a slide should be visible before the animation starts.|
