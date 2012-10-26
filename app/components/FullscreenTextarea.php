<?php

namespace NetteAddons\Components;

use Nette,
	Nette\Application\UI,
	Nette\Forms,
	Nette\Utils\Html;


class FullscreenTextarea extends UI\Control implements Forms\IControl
{
	private $ta;

	public function __construct(Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->ta = new Forms\Controls\TextArea();
		$this->addComponent($this->ta, 'ta');
	}





	protected function attached($component)
	{
		parent::attached($component);
	}





	/**
	 * Loads HTTP data.
	 *
	 * @return void
	 */
	function loadHttpData()
	{
		return $this->ta->loadHttpData();
	}

	/**
	 * Sets control's value.
	 * @param  mixed
	 * @return void
	 */
	function setValue($value)
	{
		return $this->ta->setValue($value);
	}

	/**
	 * Returns control's value.
	 * @return mixed
	 */
	function getValue()
	{
		return $this->ta->getValue();
	}

	/**
	 * @return Rules
	 */
	function getRules()
	{
		return $this->ta->getRules();
	}

	/**
	 * Returns errors corresponding to control.
	 * @return array
	 */
	function getErrors()
	{
		return $this->ta->getErrors();
	}

	/**
	 * Is control disabled?
	 * @return bool
	 */
	function isDisabled()
	{
		return $this->ta->isDisabled();
	}

	/**
	 * Returns translated string.
	 * @param  string
	 * @param  int      plural count
	 * @return string
	 */
	function translate($s, $count = NULL)
	{
		return $this->ta->translator($s, $count);
	}

	/**
	 * Is control mandatory?
	 * @return bool
	 */
	final public function isRequired()
	{
		return $this->ta->isRequired();
	}

	public function getLabel($caption = NULL)
	{
		return $this->ta->getLabel($caption);
	}

	public function getControl()
	{
		$container = Html::el('div');
		$container->addClass('fullscreen-textarea');

		$textarea = $this->ta->getControl();
		// $textarea->addClass();

		$container->add($textarea);
		$container->create('a', 'Fullscreen editor')
			->setHref($this->link('openFullscreen!'))
			->addClass('open');

		return $container;
	}

	public function handleOpenFullscreen()
	{
		$tpl = $this->createTemplate();
		$tpl->setFile(__DIR__ . '/FullscreenTextarea.latte');
		// $tpl->textarea = $this->ta;
		// $tpl->

		$this->presenter->payload->tpl = (string) $tpl;
		$this->presenter->sendPayload();
	}

}
