<?php

namespace Sebsel\Flysystem\LocalGit;

use Symfony\Component\Process\Process;

class Git {

    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function isRepository()
    {
        return strpos($this->git('status'), 'Not a git repository') === false;
    }

    public function init()
    {
        $this->git('init');
    }

    public function addAll()
    {
        $this->git('add -A');
    }

    public function commit($message)
    {
        $this->git('commit -m "' . str_replace('"', '\\"', $message) . '"');
    }

    public function git($command)
    {
        $command = "cd {$this->path} && git $command";

        $process = new Process($command);
        $process->run();

        return $process->getOutput() . $process->getErrorOutput();
    }

    public static function for($path) {
        return new static($path);
    }
}
