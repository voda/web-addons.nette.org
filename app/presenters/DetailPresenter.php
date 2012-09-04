<?php

namespace NetteAddons;

use NetteAddons\Model\Addons;
use NetteAddons\Model\AddonVersions;
use NetteAddons\Model\AddonVotes;



/**
 * @author Jan Marek
 * @author Jan Tvrdík
 */
class DetailPresenter extends BasePresenter
{
	/**
	 * @var int addon ID
	 * @persistent
	 */
	public $id;

	/** @var Addons */
	private $addons;

	/** @var AddonVersions */
	private $addonVersions;

	/** @var AddonVotes */
	private $addonVotes;



	public function injectAddons(Addons $addons)
	{
		$this->addons = $addons;
	}



	public function injectAddonVersions(AddonVersions $addonVersions)
	{
		$this->addonVersions = $addonVersions;
	}



	public function injectAddonVotes(AddonVotes $addonVotes)
	{
		$this->addonVotes = $addonVotes;
	}



	/**
	 * @param int addon ID
	 */
	public function renderDefault($id)
	{
		if (!$addon = $this->addons->find($id)) {
			$this->error('Addon not found!');
		}

		$popularity = $this->addonVotes->calculatePopularity($addon->id);
		$this->template->plus = $popularity->plus;
		$this->template->minus = $popularity->minus;
		$this->template->percents = $popularity->percent;

		$this->template->addon = $addon;

		$this->template->currentVersion = $this->addonVersions->findAddonCurrentVersion($addon);
	}



	/**
	 * Handle voting for current addon.
	 *
	 * @author Jan Tvrdík
	 * @param  string 'up' or 'down'
	 * @return void
	 */
	public function handleVote($vote)
	{
		$trans = array(
			'up' => 1,
			'cancel' => 0,
			'down' => -1,
		);

		if (!isset($trans[$vote])) {
			$this->error('invalid vote');
		} else {
			$vote = $trans[$vote];
		}

		if (!$this->user->loggedIn) {
			$this->error('not logged in', 403); // TODO: better
		}

		$this->addonVotes->vote($this->id, $this->user->id, $vote);
		$this->flashMessage('Voting was successfull!');
		$this->redirect('this');
	}
}
