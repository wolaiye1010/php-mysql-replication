<?php

namespace MySQLReplication\Event;

use MySQLReplication\BinaryDataReader\Exception\BinaryDataReaderException;
use MySQLReplication\BinLog\BinLogServerInfo;
use MySQLReplication\Event\DTO\RotateDTO;

/**
 * Class RotateEvent
 * @package MySQLReplication\Event
 * @see https://dev.mysql.com/doc/internals/en/rotate-event.html
 */
class RotateEvent extends EventCommon
{
    /**
     * @throws BinaryDataReaderException
     * @return RotateDTO
     */
    public function makeRotateEventDTO()
    {
        $pos = $this->binaryDataReader->readUInt64();
        $binFileName = $this->binaryDataReader->read(
            $this->eventInfo->getSizeNoHeader() - $this->getSizeToRemoveByVersion()
        );

        return new RotateDTO(
            $this->eventInfo,
            $pos,
            $binFileName
        );
    }

    /**
     * @return int
     */
    private function getSizeToRemoveByVersion()
    {
        if (BinLogServerInfo::MYSQL_VERSION_MARIADB !== BinLogServerInfo::getVersion())
        {
            return 8;
        }
        return 0;
    }
}