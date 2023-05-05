<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Console\GeneratorCommand;

class CrudRequest extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:crud-request';

    protected $signature = 'make:crud-request {name} {modelName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate crud request validation';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {

        $stub = parent::buildClass($name);

        $model = $this->argument('modelName');

        return $model ? $this->replaceModel($stub, $model) : $stub;
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @param  string  $model
     * @return string
     */
    protected function replaceModel($stub, $model)
    {
        $modelClass = $this->parseModel($model);
        $model = $this->argument('modelName');

        $modelObj = new $modelClass();
        $fillable = $modelObj->getFillable();

        $a = array_fill_keys($fillable, ['required']);

        $fillable = str_replace(
            ',',
            ",\n",
            str_replace(
                ['{', '}'],
                ' ',
                str_replace(':', '=>', json_encode($a))
            )
        );

        $replace = [
            '{fillable}' => $fillable,
        ];

        return  str_replace(
            array_keys($replace),
            array_values($replace),
            $stub
        );
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('stubs/request.api.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\\Requests\\' . Str::plural(trim($this->argument('modelName')));
    }
}
