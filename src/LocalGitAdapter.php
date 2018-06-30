<?php

namespace Sebsel\Flysystem\LocalGit;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Config;

class LocalGitAdapter extends Local {

    protected function commit()
    {
        $git = Git::for($this->getPathPrefix());

        if (!$git->isRepositoryRoot()) {
            $git->init();
        }

        $git->addAll();
        $git->commit('message is postponed');
    }


    /**
     * Write a new file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config   Config object
     * @return array|false false on failure file meta data on success
     */
    public function write($path, $contents, Config $config)
	{
        $return = parent::write($path, $contents, $config);

        $this->commit('created ' . basename($path));

        return $return;
    }

    /**
     * Write a new file using a stream.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config   Config object
     * @return array|false false on failure file meta data on success
     */
    public function writeStream($path, $resource, Config $config)
	{
        $return = parent::writeStream($path, $resource, $config);

        $this->commit('created ' . basename($path));

        return $return;
    }

    /**
     * Update a file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function update($path, $contents, Config $config)
	{
        $return = parent::update($path, $contents, $config);

        $this->commit('updated ' . basename($path));

        return $return;
    }

    /**
     * Update a file using a stream.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config   Config object
     * @return array|false false on failure file meta data on success
     */
    public function updateStream($path, $resource, Config $config)
	{
        $return = parent::updateStream($path, $resource, $config);

        $this->commit('updated ' . basename($path));

        return $return;
    }

    /**
     * Rename a file.
     *
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function rename($path, $newpath)
	{
        $return = parent::rename($path, $newpath);

        $this->commit('moved ' . basename($path));

        return $return;
    }

    /**
     * Copy a file.
     *
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function copy($path, $newpath)
	{
        $return = parent::copy($path, $newpath);

        $this->commit('copied ' . basename($path));

        return $return;
    }

    /**
     * Delete a file.
     *
     * @param string $path
     * @return bool
     */
    public function delete($path)
	{
        $return = parent::delete($path);

        $this->commit('deleted ' . basename($path));

        return $return;
    }

    /**
     * Delete a directory.
     *
     * @param string $dirname
     * @return bool
     */
    public function deleteDir($dirname)
	{
        $return = parent::deleteDir($dirname);

        $this->commit('deleted ' . basename($dirname));

        return $return;
    }

    /**
     * Create a directory.
     *
     * @param string $dirname directory name
     * @param Config $config
     * @return array|false
     */
    public function createDir($dirname, Config $config)
	{
        $return = parent::createDir($dirname, $config);

        $this->commit('created ' . basename($dirname));

        return $return;
    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     * @return array|false file meta data
     */
    public function setVisibility($path, $visibility)
	{
        $return = parent::createDir($path, $visibility);

        $this->commit('modified ' . basename($path));

        return $return;
    }
}
