<?php

declare(strict_types=1);

final class PdoParameterBinder
{
    /** Bind values with explicit PDO types before executing a prepared query. */
    public static function execute(PDOStatement $statement, array $parameters): bool
    {
        foreach ($parameters as $name => $value) {
            $placeholder = is_int($name) ? $name : ':' . ltrim((string) $name, ':');
            $type = match (true) {
                is_bool($value) => PDO::PARAM_BOOL,
                $value === null => PDO::PARAM_NULL,
                is_int($value) => PDO::PARAM_INT,
                default => PDO::PARAM_STR,
            };
            $statement->bindValue($placeholder, $value, $type);
        }

        return $statement->execute();
    }
}
