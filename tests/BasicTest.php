<?php

use PHPUnit\Framework\TestCase;
use League\Flysystem\Filesystem;
use Sebsel\Flysystem\LocalGit\Git;
use Sebsel\Flysystem\LocalGit\LocalGitAdapter as LocalGit;

class BasicTest extends TestCase {

    protected $fs;
    protected $root;
    protected $adapter;

    /** @setup */
    public function setup()
    {
        $this->root = '/tmp/flysystem-local-git-tests/';
        $this->adapter = new LocalGit($this->root);
        $this->fs = new Filesystem($this->adapter);
    }

    /** @teardown */
    public function teardown()
    {
        exec('rm -rf ' . $this->root);
    }

    /** @test */
    public function it_returns_false_when_it_hasnt()
    {
        $this->assertFalse($this->fs->has('hi.txt'));
    }

    /** @test */
    public function it_returns_true_when_it_has()
    {
        touch($this->root . 'hi.txt');
        $this->assertTrue($this->fs->has('hi.txt'));
    }

    /** @test */
    public function it_commits_a_new_file()
    {
        $this->assertNotContains(
            'Hi, how are you',
            Git::for($this->root)->git('log -p')
        );

        $this->fs->put('something.txt', 'Hi, how are you');

        $this->assertContains(
            'Hi, how are you',
            Git::for($this->root)->git('log -p')
        );
    }

    /** @test */
    public function it_commits_an_existing_file()
    {
        $this->fs->put('the_file.txt', 'Hi, how are you');

        $this->assertNotContains(
            'I am great, how are you?',
            Git::for($this->root)->git('log -p')
        );

        $this->fs->put('the_file.txt', "Hi, how are you\nI am great, how are you?");

        $this->assertContains(
            '+I am great, how are you?',
            Git::for($this->root)->git('log -p')
        );
    }
}
