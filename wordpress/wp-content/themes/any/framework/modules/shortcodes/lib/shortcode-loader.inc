<?php
namespace AnyEdge\Modules\Shortcodes\Lib;

use AnyEdge\Modules\ImageWithText\ImageWithText;
use AnyEdge\Modules\Shortcodes\Accordion\Accordion;
use AnyEdge\Modules\Shortcodes\AccordionTab\AccordionTab;
use AnyEdge\Modules\Shortcodes\Blockquote\Blockquote;
use AnyEdge\Modules\Shortcodes\BlogList\BlogList;
use AnyEdge\Modules\Shortcodes\BlogSlider\BlogSlider;
use AnyEdge\Modules\Shortcodes\Button\Button;
use AnyEdge\Modules\Shortcodes\CallToAction\CallToAction;
use AnyEdge\Modules\Shortcodes\Counter\Countdown;
use AnyEdge\Modules\Shortcodes\Counter\Counter;
use AnyEdge\Modules\Shortcodes\CustomFont\CustomFont;
use AnyEdge\Modules\Shortcodes\DevicePresentation\DevicePresentation;
use AnyEdge\Modules\Shortcodes\Dropcaps\Dropcaps;
use AnyEdge\Modules\Shortcodes\ElementsHolder\ElementsHolder;
use AnyEdge\Modules\Shortcodes\ElementsHolderItem\ElementsHolderItem;
use AnyEdge\Modules\Shortcodes\GoogleMap\GoogleMap;
use AnyEdge\Modules\Shortcodes\Highlight\Highlight;
use AnyEdge\Modules\Shortcodes\Icon\Icon;
use AnyEdge\Modules\Shortcodes\IconListItem\IconListItem;
use AnyEdge\Modules\Shortcodes\IconWithText\IconWithText;
use AnyEdge\Modules\Shortcodes\ImageGallery\ImageGallery;
use AnyEdge\Modules\ImageMerge\ImageMerge;
use AnyEdge\Modules\Shortcodes\Message\Message;
use AnyEdge\Modules\Shortcodes\OrderedList\OrderedList;
use AnyEdge\Modules\Shortcodes\PieCharts\PieChartBasic\PieChartBasic;
use AnyEdge\Modules\Shortcodes\PieCharts\PieChartDoughnut\PieChartDoughnut;
use AnyEdge\Modules\Shortcodes\PieCharts\PieChartDoughnut\PieChartPie;
use AnyEdge\Modules\Shortcodes\PieCharts\PieChartWithIcon\PieChartWithIcon;
use AnyEdge\Modules\Shortcodes\PricingTables\PricingTables;
use AnyEdge\Modules\Shortcodes\PricingTable\PricingTable;
use AnyEdge\Modules\Shortcodes\Process\ProcessHolder;
use AnyEdge\Modules\Shortcodes\Process\ProcessItem;
use AnyEdge\Modules\Shortcodes\ProgressBar\ProgressBar;
use AnyEdge\Modules\Shortcodes\Separator\Separator;
use AnyEdge\Modules\Shortcodes\SocialShare\SocialShare;
use AnyEdge\Modules\Shortcodes\Tabs\Tabs;
use AnyEdge\Modules\Shortcodes\Tab\Tab;
use AnyEdge\Modules\Shortcodes\Team\Team;
use AnyEdge\Modules\Shortcodes\UnorderedList\UnorderedList;
use AnyEdge\Modules\Shortcodes\VerticalSplitSlider\VerticalSplitSlider;
use AnyEdge\Modules\Shortcodes\VerticalSplitSliderContentItem\VerticalSplitSliderContentItem;
use AnyEdge\Modules\Shortcodes\VerticalSplitSliderLeftPanel\VerticalSplitSliderLeftPanel;
use AnyEdge\Modules\Shortcodes\VerticalSplitSliderRightPanel\VerticalSplitSliderRightPanel;
use AnyEdge\Modules\Shortcodes\VideoButton\VideoButton;
use AnyEdge\Modules\Shortcodes\ShopGallery\ShopGallery;
use AnyEdge\Modules\Shortcodes\Clients\Clients;
use AnyEdge\Modules\Shortcodes\Client\Client;
use AnyEdge\Modules\Shortcodes\CrossfadeImages\CrossfadeImages;

/**
 * Class ShortcodeLoader
 */
class ShortcodeLoader {
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
		$this->addShortcode(new Accordion());
		$this->addShortcode(new AccordionTab());
		$this->addShortcode(new Blockquote());
		$this->addShortcode(new BlogList());
		$this->addShortcode(new BlogSlider());
		$this->addShortcode(new Button());
		$this->addShortcode(new CallToAction());
		$this->addShortcode(new Counter());
		$this->addShortcode(new Countdown());
		$this->addShortcode(new CustomFont());
		$this->addShortcode(new DevicePresentation());
		$this->addShortcode(new Dropcaps());
		$this->addShortcode(new ElementsHolder());
		$this->addShortcode(new ElementsHolderItem());
		$this->addShortcode(new GoogleMap());
		$this->addShortcode(new Highlight());
		$this->addShortcode(new Icon());
		$this->addShortcode(new IconListItem());
		$this->addShortcode(new IconWithText());
		$this->addShortcode(new ImageGallery());
		$this->addShortcode(new ImageMerge());
		$this->addShortcode(new ImageWithText());
		$this->addShortcode(new Message());
		$this->addShortcode(new OrderedList());
		$this->addShortcode(new PieChartBasic());
		$this->addShortcode(new PieChartPie());
		$this->addShortcode(new PieChartDoughnut());
		$this->addShortcode(new PieChartWithIcon());
		$this->addShortcode(new PricingTables());
		$this->addShortcode(new PricingTable());
		$this->addShortcode(new ProgressBar());
		$this->addShortcode(new ProcessHolder());
		$this->addShortcode(new ProcessItem());
		$this->addShortcode(new Separator());
		$this->addShortcode(new SocialShare());
		$this->addShortcode(new Tabs());
		$this->addShortcode(new Tab());
		$this->addShortcode(new Team());
		$this->addShortcode(new UnorderedList());
		$this->addShortcode(new VideoButton());
		$this->addShortcode(new VerticalSplitSlider());
		$this->addShortcode(new VerticalSplitSliderLeftPanel());
		$this->addShortcode(new VerticalSplitSliderRightPanel());
		$this->addShortcode(new VerticalSplitSliderContentItem());
		$this->addShortcode(new ShopGallery());
		$this->addShortcode(new Clients());
		$this->addShortcode(new Client());
		$this->addShortcode(new CrossfadeImages());
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