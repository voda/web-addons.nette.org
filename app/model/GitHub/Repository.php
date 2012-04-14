<?php

namespace NetteAddons\Model\GitHub;

use Nette\Utils\Strings;

/**
 * @author	Patrik Votoček
 */
class Repository extends \Nette\Object
{
	/** @var ApiService */
	private $service;
	/** @var string */
	private $vendor;
	/** @var string */
	private $name;
	/** @var callable */
	private $fileFactory;

	/**
	 * @param ApiService
	 * @param callable
	 * @param string
	 * @param string
	 */
	public function __construct(ApiService $service, $fileFactory, $vendor, $name)
	{
		$this->service = $service;
		$this->vendor = $vendor;
		$this->name = $name;
		$this->fileFactory = $fileFactory;
	}

	/**
	 * @return array
	 */
	public function getTags()
	{
		$data = $this->service->exec("/repos/{$this->vendor}/{$this->name}/tags") ?: array();
		$tags = array();
		foreach ($data as $tag) {
			$tags[$tag->name] = $tag->commit->sha;
		}
		return $tags;
	}

	/**
	 * @return array
	 */
	public function getBranches()
	{
		$data = $this->service->exec("/repos/{$this->vendor}/{$this->name}/branches") ?: array();
		$branches = array();
		foreach ($data as $branche) {
			$branches[$branche->name] = $branche->commit->sha;
		}
		return $branches;
	}

	/**
	 * @param string
	 * @return string
	 */
	protected function getComposerJson($hash)
	{
		try {
			return callback($this->fileFactory)->invoke($this->vendor, $this->name, $hash)->get('composer.json');
		} catch(FileNotFoundException $e) {
			return NULL;
		}
	}

	/**
	 * @return \NetteAddons\Model\Addon|NULL
	 */
	public function getMainMetadata()
	{
		$repo = $this->service->exec("/repos/{$this->vendor}/{$this->name}");
		$branch = isset($repo->master_branch) ? $repo->master_branch : 'master';

		$data = json_decode($this->getComposerJson($branch));
		if ($data) {
			$addon = new \NetteAddons\Model\Addon;
			if (isset($data->name)) {
				list($addon->vendorName, $addon->name) = explode('/', $data->name);
			}
			if (isset($data->description)) {
				$addon->description = $data->description;
			}
			if (isset($data->keywords)) {
				$addon->tags = $data->keywords;
			}
			return $addon;
		}
	}

	/**
	 * @return \NetteAddons\Model\Addon[]|array()
	 */
	public function getVersionsMetadatas()
	{
		$versions = array_merge($this->getBranches(), $this->getTags());
		$metadatas = array();
		foreach ($versions as $v => $hash) {
			if (($data = $this->getComposerJson($hash)) && ($metadata = json_decode($data))) {
				$version = new \NetteAddons\Model\AddonVersion;
				$version->version = $v;
				$version->composerJson = json_decode($data, TRUE);

				// @todo more metadata

				$metadatas[$v] = $version;
			}
		}

		return $versions;
	}

	/**
	 * @param ApiService
	 * @param callable
	 * @param string
	 * @throws \NetteAddons\InvalidArgumentException
	 */
	public static function createFromUrl(ApiService $service, $fileFactory, $url)
	{
		if (!Strings::startsWith($url, 'http://github.com/') && Strings::startsWith($url, 'https://github.com/')) {
			throw new \NetteAddons\InvalidArgumentException("Invalid github url");
		}

		$url = new \Nette\Http\Url($url);
		list($vendor, $name) = explode('/', substr($url->getPath(), 1));
		return new static($service, $fileFactory, $vendor, $name);
	}
}
