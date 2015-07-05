<?php

namespace Clusteramaryllis\Gettext\Command;

class GettextCreateCommand extends BaseCommand
{
    /**
     * Name of the command.
     *
     * @var string
     */
    protected $name = 'gettext:create';

    /**
     * Command description.
     *
     * @var string
     */
    protected $description = 'Create new po files based on the given paths';

    /**
     * {@inheritDoc}
     */
    public function fire()
    {
        $cached = false;

        foreach ($this->getLanguages() as $language) {
            $domain          = $this->getDomain();
            $destinationPath = $this->getDestinationPath();

            $this->generator->addLocale(
                $this->getPaths(),
                $this->getStoragePath(),
                $destinationPath,
                $domain,
                $language['locale'],
                $language['encoding'],
                $this->getProject(),
                $this->getTranslator(),
                $this->getKeywords(),
                $language['plural_forms'],
                $cached
            );

            $cached = true;
            $info   = $this->generator->domainPath(
                $destinationPath,
                $language['locale']
            )."/{$domain}.po";

            $this->output->writeln('<info>Po file created on :</info> '.$info);
        }
    }
}
