<?php

declare(strict_types=1);

namespace <namespace>;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class <className> extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    /**
    * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    */
    public function up(Schema $schema): void
    {
<up>
    }

    /**
    * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    */
    public function down(Schema $schema): void
    {
<down>
    }
}
