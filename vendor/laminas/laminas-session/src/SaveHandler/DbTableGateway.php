<?php

namespace Laminas\Session\SaveHandler;

use Laminas\Db\TableGateway\TableGateway;
use ReturnTypeWillChange;

use function ini_get;
use function sprintf;
use function time;

/**
 * DB Table Gateway session save handler
 *
 * @see ReturnTypeWillChange
 */
class DbTableGateway implements SaveHandlerInterface
{
    /**
     * Session Save Path
     *
     * @var string
     */
    protected $sessionSavePath;

    /**
     * Session Name
     *
     * @var string
     */
    protected $sessionName;

    /**
     * Lifetime
     *
     * @var int
     */
    protected $lifetime;

    /**
     * Laminas Db Table Gateway
     *
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * DbTableGateway Options
     *
     * @var DbTableGatewayOptions
     */
    protected $options;

    /**
     * Constructor
     */
    public function __construct(TableGateway $tableGateway, DbTableGatewayOptions $options)
    {
        $this->tableGateway = $tableGateway;
        $this->options      = $options;
    }

    /**
     * Open Session
     *
     * @param  string $savePath
     * @param  string $name
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function open($savePath, $name)
    {
        $this->sessionSavePath = $savePath;
        $this->sessionName     = $name;
        $this->lifetime        = ini_get('session.gc_maxlifetime');

        return true;
    }

    /**
     * Close session
     *
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function close()
    {
        return true;
    }

    /**
     * Read session data
     *
     * @param string $id
     * @param bool $destroyExpired Optional; true by default
     * @return string
     */
    #[ReturnTypeWillChange]
    public function read($id, $destroyExpired = true)
    {
        $row = $this->tableGateway->select([
            $this->options->getIdColumn()   => $id,
            $this->options->getNameColumn() => $this->sessionName,
        ])->current();

        if ($row) {
            if (
                $row->{$this->options->getModifiedColumn()} +
                $row->{$this->options->getLifetimeColumn()} > time()
            ) {
                return (string) $row->{$this->options->getDataColumn()};
            }
            if ($destroyExpired) {
                $this->destroy($id);
            }
        }
        return '';
    }

    /**
     * Write session data
     *
     * @param string $id
     * @param string $data
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function write($id, $data)
    {
        $data = [
            $this->options->getModifiedColumn() => time(),
            $this->options->getDataColumn()     => (string) $data,
        ];

        $rows = $this->tableGateway->select([
            $this->options->getIdColumn()   => $id,
            $this->options->getNameColumn() => $this->sessionName,
        ])->current();

        if ($rows) {
            return (bool) $this->tableGateway->update($data, [
                $this->options->getIdColumn()   => $id,
                $this->options->getNameColumn() => $this->sessionName,
            ]);
        }
        $data[$this->options->getLifetimeColumn()] = $this->lifetime;
        $data[$this->options->getIdColumn()]       = $id;
        $data[$this->options->getNameColumn()]     = $this->sessionName;

        return (bool) $this->tableGateway->insert($data);
    }

    /**
     * Destroy session
     *
     * @param  string $id
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function destroy($id)
    {
        $this->tableGateway->delete([
            $this->options->getIdColumn()   => $id,
            $this->options->getNameColumn() => $this->sessionName,
        ]);

        return true;
    }

    /**
     * Garbage Collection
     *
     * @param int $maxlifetime
     * @return true
     */
    #[ReturnTypeWillChange]
    public function gc($maxlifetime)
    {
        $platform = $this->tableGateway->getAdapter()->getPlatform();
        return (bool) $this->tableGateway->delete(
            sprintf(
                '%s < %d',
                $platform->quoteIdentifier($this->options->getModifiedColumn()),
                time() - $this->lifetime
            )
        );
    }
}
