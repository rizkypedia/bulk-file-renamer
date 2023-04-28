<?php

namespace FileRenamer\Command;

use FileRenamer\Filehandler\Renamer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class FileRenamerCommand extends Command
{
    protected static $defaultName = 'run';

    protected function configure()
    {
        $this->setHelp('This command allows renaming files in a bulk');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Bulk File Renamer');
        $folder = $io->ask('Please Provide the path to the folder with your files', 'files', function ($answer) {
            if (empty($answer)) {
                return "files";
            }

            return $answer;
        });

        $newFileName = $io->ask('Please provider a new filename', 'default', function ($answer) {
            if (empty($answer)) {
                return "file";
            }
            return $answer;
        });


        $renamer = new Renamer($folder, $newFileName);
        $files = $renamer->renameFiles();
        if (empty($files)) {
            $output->writeln('Renaming Files Unsuccessfull!');
            return Command::FAILURE;
        }
        $output->writeln('Files has been Renamed!');
        return Command::SUCCESS;
    }


}
