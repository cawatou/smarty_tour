<?php

DxFactory::import('DxAppContext');
DxFactory::import('DxCommandHook');
DxFactory::import('DxCommand');

abstract class DxController
{
    const DX_CONTROLLER_ERROR_BASE                  = 600;
    const DX_CONTROLLER_ERROR_NO_SUCH_METHOD        = 601;
    const DX_CONTROLLER_INVOCATION_TARGET_EXCEPTION = 602;

    /** @var DxAppContext */
    protected $ctx;

    /** @var null|DxCommandHook */
    protected $hook = null;

    /**
     * @param DxAppContext         $ctx
     * @param DxCommandHook|null   $hook
     */
    public function __construct(DxAppContext $ctx, DxCommandHook $hook = null)
    {
        $this->setContext($ctx);
        $this->setHook($hook);
    }

    /**
     * @abstract
     * @param DxCommand $command
     * @return string
     */
    protected abstract function getCommandMethod(DxCommand $command);

    public final function execute()
    {
        $command = $this->getContext()->getCurrentCommand();
        $user    = $this->getContext()->getCurrentUser();

        try {
            if (!is_null($this->getHook())) {
                $this->getHook()->execute($command, $user, DxCommandHook::DX_COMMANDHOOK_EVENT_BEFORE);
            }

            $class         = get_class($this);
            $class_methods = get_class_methods($class);
            $method_name   = $this->getCommandMethod($command);

            if (empty($method_name)) {
                throw new DxException("The class '{$class}' there is no method for command '{$command->getCmd()}'", self::DX_CONTROLLER_ERROR_NO_SUCH_METHOD);
            }

            try {
                if (!in_array($method_name, $class_methods)) {
                    throw new DxException("No such method '{$method_name}' in class {$class}", self::DX_CONTROLLER_ERROR_NO_SUCH_METHOD);
                } else {
                    $response = $this->$method_name();
                }
            } catch (Exception $e) {
                throw new DxException("Exception occured while invoke method '{$method_name}' in class {$class}", self::DX_CONTROLLER_INVOCATION_TARGET_EXCEPTION, $e);
            }

            if (!is_null($this->getHook())) {
                $this->getHook()->execute($command, $user, DxCommandHook::DX_COMMANDHOOK_EVENT_AFTER);
            }
        } catch (Exception $e) {
            throw new DxException("Failed execute command '{$command->getCmd()}'", DxApp::DX_APP_ERROR_EXECUTE_COMMAND, $e);
        }

        return $response;
    }

    /**
     * @param DxAppContext $ctx
     */
    public function setContext(DxAppContext $ctx)
    {
        $this->ctx = $ctx;
    }

    /**
     * @param DxCommandHook|null $hook
     */
    public function setHook(DxCommandHook $hook = null)
    {
        $this->hook = $hook;
    }

    /**
     * @return DxAppContext
     */
    public function getContext()
    {
        return $this->ctx;
    }

    /**
     * @return DxCommandHook
     */
    public function getHook()
    {
        return $this->hook;
    }

}