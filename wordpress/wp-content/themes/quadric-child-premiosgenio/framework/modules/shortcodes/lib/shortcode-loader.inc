<?php

namespace EdgefQuadricModules\Shortcodes\Lib;

use EdgefQuadricModules\BackgroundRevealingHolder\BackgroundRevealingHolder;
use EdgefQuadricModules\CallToAction\CallToAction;
use EdgefQuadricModules\Counter\Countdown;
use EdgefQuadricModules\Counter\Counter;
use EdgefQuadricModules\Clients\Clients;
use EdgefQuadricModules\Client\Client;
use EdgefQuadricModules\SvgIcons\SvgIcons;
use EdgefQuadricModules\Number\Number;
use EdgefQuadricModules\ZigZagSeparator\ZigZagSeparator;
use EdgefQuadricModules\ElementsHolder\ElementsHolder;
use EdgefQuadricModules\ElementsHolderItem\ElementsHolderItem;
use EdgefQuadricModules\GoogleMap\GoogleMap;
use EdgefQuadricModules\Separator\Separator;
use EdgefQuadricModules\PieCharts\PieChartBasic\PieChartBasic;
use EdgefQuadricModules\PieCharts\PieChartDoughnut\PieChartDoughnut;
use EdgefQuadricModules\PieCharts\PieChartDoughnut\PieChartPie;
use EdgefQuadricModules\PieCharts\PieChartWithIcon\PieChartWithIcon;
use EdgefQuadricModules\Shortcodes\Icon\Icon;
use EdgefQuadricModules\InfoCard\InfoCard;
use EdgefQuadricModules\Shortcodes\ImageGallery\ImageGallery;
use EdgefQuadricModules\SocialShare\SocialShare;
use EdgefQuadricModules\Team\Team;
use EdgefQuadricModules\TeamSliderHolder\TeamSliderHolder;
use EdgefQuadricModules\TeamSliderItem\TeamSliderItem;
use EdgefQuadricModules\OrderedList\OrderedList;
use EdgefQuadricModules\UnorderedList\UnorderedList;
use EdgefQuadricModules\Message\Message;
use EdgefQuadricModules\ProgressBar\ProgressBar;
use EdgefQuadricModules\IconListItem\IconListItem;
use EdgefQuadricModules\Tabs\Tabs;
use EdgefQuadricModules\Tab\Tab;
use EdgefQuadricModules\PricingTables\PricingTables;
use EdgefQuadricModules\PricingTable\PricingTable;
use EdgefQuadricModules\Accordion\Accordion;
use EdgefQuadricModules\AccordionTab\AccordionTab;
use EdgefQuadricModules\BlogList\BlogList;
use EdgefQuadricModules\InteractiveBanner\InteractiveBanner;
use EdgefQuadricModules\Shortcodes\Button\Button;
use EdgefQuadricModules\Blockquote\Blockquote;
use EdgefQuadricModules\CustomFont\CustomFont;
use EdgefQuadricModules\Highlight\Highlight;
use EdgefQuadricModules\VideoButton\VideoButton;
use EdgefQuadricModules\Dropcaps\Dropcaps;
use EdgefQuadricModules\Shortcodes\IconWithText\IconWithText;
use EdgefQuadricModules\IconSliderHolder\IconSliderHolder;
use EdgefQuadricModules\IconSliderItem\IconSliderItem;
use EdgefQuadricModules\ContentSliderHolder\ContentSliderHolder;
use EdgefQuadricModules\ContentSliderItem\ContentSliderItem;
use EdgefQuadricModules\ZoomingSliderHolder\ZoomingSliderHolder;
use EdgefQuadricModules\ZoomingSliderItem\ZoomingSliderItem;
use EdgefQuadricModules\ProcessHolder\ProcessHolder;
use EdgefQuadricModules\ProcessHolderItem\ProcessHolderItem;
use EdgefQuadricModules\FloatingSlider\FloatingSlider;
use EdgefQuadricModules\FloatingSlide\FloatingSlide;
use EdgefQuadricModules\FloatingSlideImage\FloatingSlideImage;
use EdgefQuadricModules\FloatingSlidePost\FloatingSlidePost;
use EdgefQuadricModules\FloatingSlideElement\FloatingSlideElement;


/**
 * Class ShortcodeLoader
 */
class ShortcodeLoader
{
	/**
	 * @var private instance of current class
	 */
	private static $instance;
	/**
	 * @var array
	 */
	private $loadedShortcodes = array();

	/**
	 * Private constuct because of Singletone
	 */
	private function __construct() {}

	/**
	 * Private sleep because of Singletone
	 */
	private function __wakeup() {}

	/**
	 * Private clone because of Singletone
	 */
	private function __clone() {}

	/**
	 * Returns current instance of class
	 * @return ShortcodeLoader
	 */
	public static function getInstance() {
		if(self::$instance == null) {
			return new self;
		}

		return self::$instance;
	}

	/**
	 * Adds new shortcode. Object that it takes must implement ShortcodeInterface
	 * @param ShortcodeInterface $shortcode
	 */
	private function addShortcode(ShortcodeInterface $shortcode) {
		if(!array_key_exists($shortcode->getBase(), $this->loadedShortcodes)) {
			$this->loadedShortcodes[$shortcode->getBase()] = $shortcode;
		}
	}

	/**
	 * Adds all shortcodes.
	 *
	 * @see ShortcodeLoader::addShortcode()
	 */
	private function addShortcodes() {
		$this->addShortcode(new ElementsHolder());
		$this->addShortcode(new ElementsHolderItem());
		$this->addShortcode(new Team());
		$this->addShortcode(new Icon());
		$this->addShortcode(new CallToAction());
		$this->addShortcode(new Clients());
		$this->addShortcode(new Client());
		$this->addShortcode(new ZigZagSeparator());
		$this->addShortcode(new SvgIcons());
		$this->addShortcode(new Number());
		$this->addShortcode(new BackgroundRevealingHolder());
		$this->addShortcode(new OrderedList());
		$this->addShortcode(new UnorderedList());
		$this->addShortcode(new Message());
		$this->addShortcode(new Counter());
		$this->addShortcode(new Countdown());
		$this->addShortcode(new ProgressBar());
		$this->addShortcode(new IconListItem());
		$this->addShortcode(new InfoCard());
		$this->addShortcode(new Tabs());
		$this->addShortcode(new Tab());
		$this->addShortcode(new PricingTables());
		$this->addShortcode(new PricingTable());
		$this->addShortcode(new PieChartBasic());
		$this->addShortcode(new PieChartPie());
		$this->addShortcode(new PieChartDoughnut());
		$this->addShortcode(new PieChartWithIcon());
		$this->addShortcode(new Accordion());
		$this->addShortcode(new AccordionTab());
		$this->addShortcode(new BlogList());
		$this->addShortcode(new InteractiveBanner());
		$this->addShortcode(new Button());
		$this->addShortcode(new Blockquote());
		$this->addShortcode(new CustomFont());
		$this->addShortcode(new Highlight());
		$this->addShortcode(new ImageGallery());
		$this->addShortcode(new GoogleMap());
		$this->addShortcode(new Separator());
		$this->addShortcode(new VideoButton());
		$this->addShortcode(new Dropcaps());
		$this->addShortcode(new IconWithText());
		$this->addShortcode(new SocialShare());
		$this->addShortcode(new IconSliderHolder());
		$this->addShortcode(new IconSliderItem());
		$this->addShortcode(new ContentSliderHolder());		
		$this->addShortcode(new ContentSliderItem());
		$this->addShortcode(new ZoomingSliderHolder());
		$this->addShortcode(new ZoomingSliderItem());
		$this->addShortcode(new ProcessHolder());
		$this->addShortcode(new ProcessHolderItem());
		$this->addShortcode(new FloatingSlider());
		$this->addShortcode(new FloatingSlide());
		$this->addShortcode(new FloatingSlideImage());
		$this->addShortcode(new FloatingSlidePost());
		$this->addShortcode(new FloatingSlideElement());
		$this->addShortcode(new TeamSliderHolder());		
		$this->addShortcode(new TeamSliderItem());
	}
	/**
	 * Calls ShortcodeLoader::addShortcodes and than loops through added shortcodes and calls render method
	 * of each shortcode object
	 */
	public function load() {
		$this->addShortcodes();

		foreach ($this->loadedShortcodes as $shortcode) {
			add_shortcode($shortcode->getBase(), array($shortcode, 'render'));
		}

	}
}

$shortcodeLoader = ShortcodeLoader::getInstance();
$shortcodeLoader->load();