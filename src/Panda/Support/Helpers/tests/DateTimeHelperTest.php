<?php

namespace Panda\Support\Helpers\tests;

use DateTime;
use Panda\Support\Helpers\DateTimeHelper;
use PHPUnit_Framework_TestCase;

/**
 * Class DateTimeHelperTest
 * @package Panda\Support\Helpers\tests
 */
class DateTimeHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Panda\Support\Helpers\DateTimeHelper::compareDateWithoutTime
     */
    public function testCompareDateWithoutTime()
    {
        // Equals
        $this->assertEquals(0, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-01-01 12:00:12'), new DateTime('2017-01-01 12:00:12')));
        $this->assertEquals(0, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-01-01 12:00:12'), new DateTime('2017-01-01 15:00:12')));
        $this->assertEquals(0, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-01-01'), new DateTime('2017-01-01')));

        // Date1 < Date2
        $this->assertEquals(-1, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-05-04 12:00:12'), new DateTime('2017-05-05 15:00:12')));
        $this->assertEquals(-1, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-04-05 12:00:12'), new DateTime('2017-05-05 15:00:12')));
        $this->assertEquals(-1, DateTimeHelper::compareDateWithoutTime(new DateTime('2016-05-05 12:00:12'), new DateTime('2017-05-05 15:00:12')));

        // Date1 > Date2
        $this->assertEquals(1, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-05-05 15:00:12'), new DateTime('2017-05-04 12:00:12')));
        $this->assertEquals(1, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-05-05 15:00:12'), new DateTime('2017-04-05 12:00:12')));
        $this->assertEquals(1, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-05-05 15:00:12'), new DateTime('2016-05-05 12:00:12')));
    }

    /**
     * @covers \Panda\Support\Helpers\DateTimeHelper::compareTimeWithoutDate
     */
    public function testCompareTimeWithoutDate()
    {
        // Equals
        $this->assertEquals(0, DateTimeHelper::compareTimeWithoutDate(new DateTime('2017-01-01 12:00:12'), new DateTime('2017-01-01 12:00:12')));
        $this->assertEquals(0, DateTimeHelper::compareTimeWithoutDate(new DateTime('2017-02-02 12:00:12'), new DateTime('2017-01-01 12:00:12')));
        $this->assertEquals(0, DateTimeHelper::compareTimeWithoutDate(new DateTime('2017-01-01'), new DateTime('2017-01-01')));

        // Time1 < Time2
        $this->assertEquals(-1, DateTimeHelper::compareTimeWithoutDate(new DateTime('2017-05-04 12:00:12'), new DateTime('2017-05-05 12:00:13')));
        $this->assertEquals(-1, DateTimeHelper::compareTimeWithoutDate(new DateTime('2017-05-04 12:00:12'), new DateTime('2017-05-05 12:30:12')));
        $this->assertEquals(-1, DateTimeHelper::compareTimeWithoutDate(new DateTime('2017-04-05 12:00:12'), new DateTime('2017-05-05 15:00:12')));
        $this->assertEquals(-1, DateTimeHelper::compareTimeWithoutDate(new DateTime('2016-05-05 12:00:12'), new DateTime('2017-05-05 15:30:12')));

        // Time1 > Time2
        $this->assertEquals(1, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-05-05 15:00:12'), new DateTime('2017-05-04 12:00:12')));
        $this->assertEquals(1, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-05-05 15:30:12'), new DateTime('2017-04-05 12:00:12')));
        $this->assertEquals(1, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-05-05 12:30:12'), new DateTime('2016-05-05 12:00:12')));
        $this->assertEquals(1, DateTimeHelper::compareDateWithoutTime(new DateTime('2017-05-05 12:00:13'), new DateTime('2016-05-05 12:00:12')));
    }

    /**
     * @covers \Panda\Support\Helpers\DateTimeHelper::compareDateWithHourMinuteSecond
     */
    public function testCompareDateWithHourMinuteSecond()
    {
        // Equal
        $this->assertEquals(0, DateTimeHelper::compareDateWithHourMinuteSecond(new DateTime('2017-05-05 00:00:00'), 0, 0, 0));
        $this->assertEquals(0, DateTimeHelper::compareDateWithHourMinuteSecond(new DateTime('2017-05-05 12:00:13'), 12, 0, 13));
        $this->assertEquals(0, DateTimeHelper::compareDateWithHourMinuteSecond(new DateTime('2017-05-05 12:50:13'), 12, 50, 13));

        // Hour1 < Hour2
        $this->assertEquals(-1, DateTimeHelper::compareDateWithHourMinuteSecond(new DateTime('2017-05-05 00:00:00'), 0, 1, 0));
        $this->assertEquals(-1, DateTimeHelper::compareDateWithHourMinuteSecond(new DateTime('2017-05-05 12:00:13'), 12, 10, 13));
        $this->assertEquals(-1, DateTimeHelper::compareDateWithHourMinuteSecond(new DateTime('2017-05-05 12:50:13'), 12, 51, 13));
        $this->assertEquals(-1, DateTimeHelper::compareDateWithHourMinuteSecond(new DateTime('2017-05-05 12:50:13'), 12, 50, 15));

        // Hour1 > Hour2
        $this->assertEquals(1, DateTimeHelper::compareDateWithHourMinuteSecond(new DateTime('2017-05-05 12:00:13'), 11, 00, 13));
        $this->assertEquals(1, DateTimeHelper::compareDateWithHourMinuteSecond(new DateTime('2017-05-05 12:50:13'), 12, 49, 13));
        $this->assertEquals(1, DateTimeHelper::compareDateWithHourMinuteSecond(new DateTime('2017-05-05 12:50:13'), 12, 50, 11));
    }

    /**
     * @covers \Panda\Support\Helpers\DateTimeHelper::getWorkingDaysCount
     */
    public function testGetWorkingDaysCount()
    {
        /**
         * Notes
         *
         * 2017-06-05: Monday
         */

        $this->assertEquals(7, DateTimeHelper::getWorkingDaysCount(new DateTime('2017-06-05'), new DateTime('2017-06-11'), []));
        $this->assertEquals(5, DateTimeHelper::getWorkingDaysCount(new DateTime('2017-06-05'), new DateTime('2017-06-11'), [6, 7]));
        $this->assertEquals(5, DateTimeHelper::getWorkingDaysCount(new DateTime('2017-06-05'), new DateTime('2017-06-11'), [1, 2]));

        $this->assertEquals(5, DateTimeHelper::getWorkingDaysCount(new DateTime('2017-06-05'), new DateTime('2017-06-12'), [1, 2]));
        $this->assertEquals(5, DateTimeHelper::getWorkingDaysCount(new DateTime('2017-06-05'), new DateTime('2017-06-13'), [1, 2]));
        $this->assertEquals(7, DateTimeHelper::getWorkingDaysCount(new DateTime('2017-06-05'), new DateTime('2017-06-13'), [6, 7]));
        $this->assertEquals(7, DateTimeHelper::getWorkingDaysCount(new DateTime('2017-06-05'), new DateTime('2017-06-13'), [6, 7, 8, 9]));
    }

    /**
     * @covers \Panda\Support\Helpers\DateTimeHelper::getMinutesCount
     */
    public function testGetMinutesCount()
    {
        // Just minutes and hours
        $this->assertEquals(0, DateTimeHelper::getMinutesCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-05 00:00:00')));
        $this->assertEquals(10, DateTimeHelper::getMinutesCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-05 00:10:00')));
        $this->assertEquals(70, DateTimeHelper::getMinutesCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-05 01:10:00')));

        // Add days
        $this->assertEquals(24 * 60, DateTimeHelper::getMinutesCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 00:00:00')));
        $this->assertEquals(24 * 60 + 10, DateTimeHelper::getMinutesCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 00:10:00')));
        $this->assertEquals(24 * 60 + 70, DateTimeHelper::getMinutesCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 01:10:00')));

        // Check absolute count
        $this->assertEquals(24 * 60, DateTimeHelper::getMinutesCount(new DateTime('2017-05-06 00:00:00'), new DateTime('2017-05-05 00:00:00')));
        $this->assertEquals(24 * 60 + 10, DateTimeHelper::getMinutesCount(new DateTime('2017-05-06 00:10:00'), new DateTime('2017-05-05 00:00:00')));
        $this->assertEquals(24 * 60 + 70, DateTimeHelper::getMinutesCount(new DateTime('2017-05-06 01:10:00'), new DateTime('2017-05-05 00:00:00')));

        // Check not to count seconds
        $this->assertEquals(24 * 60, DateTimeHelper::getMinutesCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 00:00:23')));
        $this->assertEquals(24 * 60 + 10, DateTimeHelper::getMinutesCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 00:10:12')));
        $this->assertEquals(24 * 60 + 70, DateTimeHelper::getMinutesCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 01:10:54')));
    }

    /**
     * @covers \Panda\Support\Helpers\DateTimeHelper::getSecondsCount
     */
    public function testGetSecondsCount()
    {
        // Just minutes and hours
        $this->assertEquals(0, DateTimeHelper::getSecondsCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-05 00:00:00')));
        $this->assertEquals(10 * 60, DateTimeHelper::getSecondsCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-05 00:10:00')));
        $this->assertEquals(70 * 60, DateTimeHelper::getSecondsCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-05 01:10:00')));

        // Add days
        $this->assertEquals(24 * 60 * 60, DateTimeHelper::getSecondsCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 00:00:00')));
        $this->assertEquals((24 * 60 + 10) * 60, DateTimeHelper::getSecondsCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 00:10:00')));
        $this->assertEquals((24 * 60 + 70) * 60, DateTimeHelper::getSecondsCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 01:10:00')));

        // Check absolute count
        $this->assertEquals(24 * 60 * 60, DateTimeHelper::getSecondsCount(new DateTime('2017-05-06 00:00:00'), new DateTime('2017-05-05 00:00:00')));
        $this->assertEquals((24 * 60 + 10) * 60, DateTimeHelper::getSecondsCount(new DateTime('2017-05-06 00:10:00'), new DateTime('2017-05-05 00:00:00')));
        $this->assertEquals((24 * 60 + 70) * 60, DateTimeHelper::getSecondsCount(new DateTime('2017-05-06 01:10:00'), new DateTime('2017-05-05 00:00:00')));

        // Check to count seconds
        $this->assertEquals(24 * 60 * 60 + 23, DateTimeHelper::getSecondsCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 00:00:23')));
        $this->assertEquals((24 * 60 + 10) * 60 + 12, DateTimeHelper::getSecondsCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 00:10:12')));
        $this->assertEquals((24 * 60 + 70) * 60 + 54, DateTimeHelper::getSecondsCount(new DateTime('2017-05-05 00:00:00'), new DateTime('2017-05-06 01:10:54')));
    }
}
