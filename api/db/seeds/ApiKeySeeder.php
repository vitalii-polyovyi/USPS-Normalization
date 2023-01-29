<?php


use Phinx\Seed\AbstractSeed;

class ApiKeySeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $key = 'localhost-key';
        $apiKeys = $this->table('api_keys');
        $apiKey = $this->fetchRow('SELECT * FROM `api_keys` WHERE `key` = "' . $key . '" LIMIT 1');
        if (!$apiKey) {
            $apiKeys
                ->insert([
                    'key' => $key,
                    'domains' => '["localhost"]',
                ])
                ->saveData();
        }
    }
}
