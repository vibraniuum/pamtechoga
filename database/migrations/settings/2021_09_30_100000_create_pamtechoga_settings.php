<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreatePamtechogaSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('pamtechoga.enabled', false);
        // $this->migrator->add('pamtechoga.url', '');
        // $this->migrator->addEncrypted('pamtechoga.access_token', '');
    }

    public function down()
    {
        $this->migrator->delete('pamtechoga.enabled');
        // $this->migrator->delete('pamtechoga.url');
        // $this->migrator->delete('pamtechoga.access_token');
    }
}
