<?php

return [
    'table_storage' => [
        'table_name' => 'doctrine_migration_versions',
        'version_column_length' => 191,
        'version_column_name' => 'version',
        'executed_at_column_name' => 'executed_at',
        'execution_time_column_name' => 'execution_time',
    ],
    'migrations_paths' => [
        'Migrations' => __DIR__ . '/migrations',
    ],
    'all_or_nothing' => true,
    'check_database_platform' => true,
];