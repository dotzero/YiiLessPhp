<?php
/**
 * ELessCompiler class file.
 *
 * @package YiiLessPhp
 * @author dZ <mail@dotzero.ru>
 * @link http://www.dotzero.ru
 * @link https://github.com/dotzero/YiiLessPhp
 * @license MIT
 * @version 1.0 (24-nov-2013)
 */

/**
 * ELessPhp is an extension for the Yii PHP framework that allows developers to compile LESS files into CSS
 * on the fly, using the LessPhp compiler.
 *
 * Requirements:
 * Yii Framework 1.1.0 or later
 *
 * Installation:
 * - Extract ELessPhp folder under 'protected/extensions'
 * - Download and extract LessPhp (http://leafo.net/lessphp/) under 'protected/vendor'
 * - Add the following to your config file 'components' section:
 *
 *  // Add extension to preload section
 *  'preload' => array(
 *      'less',
 *  ),
 *
 *  // Add extension to components section
 *  'less' => array(
 *      'class' => 'ext.ELessPhp.ELessCompiler',
 *      'lessphpDir' => 'application.vendors.lessphp',
 *      'forceCompile' => false,
 *      'files' => array(
 *          'css/style.less' => 'css/style.css',
 *          'css/userstyle.less' => 'css/userstyle.css'
 *      ),
 *  ),
 */
class ELessCompiler extends CApplicationComponent
{
    /**
     * @var string Path alias of the directory where the lessc.inc.php file can be found
     */
    public $lessphpDir = 'application.vendors.lessphp';

    /**
     * @var bool Force recompile LESS into CSS every initializes the component
     */
    public $forceCompile = false;

    /**
     * @var array List of the LESS files to compile into CSS
     */
    public $files = array();

    /**
     * @var null|string Absolute path to application the base path
     */
    private $basePath = null;

    /**
     * @var null|lessc Instance of LessPhp compiler
     */
    private $lessphp = null;

    /**
     * Initializes the application component.
     *
     * @throws CException
     */
    public function init()
    {
        parent::init();

        // adding LessPhp library directory to include path
        Yii::import($this->lessphpDir . '.*');

        // including LessPhp class
        require_once('lessc.inc.php');

        if ($this->basePath === null) {
            $this->basePath = Yii::getPathOfAlias('webroot');
        }

        if (!is_array($this->files)) {
            throw new CException('Failed to compile LESS. Property files must be an array.');
        }

        foreach ($this->files AS $fileLess => $fileCss) {
            $pathLess = $this->basePath . '/' . $fileLess;
            $pathCss = $this->basePath . '/' . $fileCss;

            try {
                if (file_exists($pathLess)) {
                    if ($this->forceCompile === true) {
                        $this->getLessphp()->compileFile($pathLess, $pathCss);
                    } else {
                        $this->getLessphp()->checkedCompile($pathLess, $pathCss);

                    }
                }
            } catch (Exception $e) {
                throw new CException(__CLASS__ . ': ' . Yii::t(
                        'less',
                        'Failed to compile less file with message: `{message}`.',
                        array('{message}' => $e->getMessage())
                    ));
            }
        }


    }

    /**
     * Return LessPhp instance
     *
     * @return lessc
     */
    public function getLessphp()
    {
        if ($this->lessphp === null) {
            $this->lessphp = new lessc();
        }

        return $this->lessphp;
    }
}
