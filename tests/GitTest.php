<?php

use PHPUnit\Framework\TestCase;
use Sebsel\Flysystem\LocalGit\Git;

class GitTest extends TestCase {

    protected $root;

    /** @setup */
    public function setup()
    {
        $this->root = '/tmp/flysystem-local-git-tests/';
        if (!file_exists($this->root)) mkdir($this->root);
    }

    /** @teardown */
    public function teardown()
    {
        shell_exec('rm -rf ' . $this->root);
    }

    /** @test */
    public function it_detects_git_repositories()
    {
        $this->assertFalse(
            Git::for($this->root)->isRepository(),
            'Failed to assert that ' . $this->root . ' is not a git repository.'
        );

        shell_exec('cd ' . $this->root . ' && git init');

        $this->assertTrue(
            Git::for($this->root)->isRepository(),
            'Failed to assert that ' . $this->root . ' is a git repository.'
        );
    }

        /** @test */
    public function it_detects_the_root_of_git_repositories()
    {
        shell_exec('cd ' . $this->root . ' && git init');
        mkdir($this->root . 'subfolder');

        $this->assertTrue(
            Git::for($this->root . 'subfolder')->isRepository(),
            'Failed to assert that ' . $this->root . '/subfolder is a git repository.'
        );

        $this->assertFalse(
            Git::for($this->root . 'subfolder')->isRepositoryRoot(),
            'Failed to assert that ' . $this->root . 'subfolder is the root of a git repository.'
        );

        $this->assertTrue(
            Git::for($this->root)->isRepositoryRoot(),
            'Failed to assert that ' . $this->root . ' is the root of a git repository.'
        );
    }

    /** @test */
    public function it_inits_git_repositories()
    {
        $this->assertFalse(Git::for($this->root)->isRepository());

        Git::for($this->root)->init();

        $this->assertTrue(
            Git::for($this->root)->isRepository(),
            'Failed to assert that init() created a repository.'
        );
    }

    /** @test */
    public function it_commits_to_repositories()
    {
        $git = Git::for($this->root);
        $git->init();

        $status = $git->git('status');
        $this->assertContains("No commits yet", $status);
        $this->assertNotContains("file.txt", $status);

        touch($this->root . 'file.txt');

        $this->assertContains("file.txt", $git->git('status'));
        $this->assertNotContains("file.txt", $git->git('diff --cached --name-only'));

        $git->addAll();

        $this->assertContains("file.txt", $git->git('diff --cached --name-only'));
        $this->assertNotContains("bananas", $git->git('log'));

        $git->commit("bananas");

        $this->assertContains("bananas", $git->git('log'));
    }
}
