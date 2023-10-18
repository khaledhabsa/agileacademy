<?php
namespace Bookly\Lib\Utils\Ics;

use Bookly\Lib\DataHolders\Booking\Simple;
use Bookly\Lib\UserBookingData;
use Bookly\Lib\Utils\Common;
use Bookly\Lib\Entities\CustomerAppointment;
use Bookly\Lib;

class Feed
{
    /** @var Event[] */
    protected $events = array();

    /**
     * @return string
     */
    public function render()
    {
        $content = '';
        foreach ( $this->events as $event ) {
            $content .= $event->render();
        }

        return "BEGIN:VCALENDAR\r\n"
            . "VERSION:2.0\r\n"
            . "PRODID:-//Bookly\r\n"
            . "CALSCALE:GREGORIAN\r\n"
            . $content
            . 'END:VCALENDAR';
    }

    /**
     * @param string $start_date
     * @param string $end_date
     * @param string $summary
     * @param string $description
     * @param int $location_id
     * @return $this
     */
    public function addEvent( $start_date, $end_date, $summary, $description, $location_id = null )
    {
        $event = new Event();
        $event
            ->setStartDate( $start_date )
            ->setEndDate( $end_date )
            ->setLocationId( $location_id )
            ->setSummary( $summary )
            ->setDescription( $description );
        $this->events[] = $event;

        return $this;
    }

    /**
     * @param UserBookingData $userData
     * @return Feed
     */
    public static function createFromBookingData( UserBookingData $userData )
    {
        // Generate ICS feed.
        $ics = new self();

        if ( $userData->load() && $userData->getOrderId() ) {
            $records = CustomerAppointment::query( 'ca' )
                ->select( 's.id AS service_id, MIN(a.start_date) AS start_date, MAX(a.end_date) AS end_date, a.custom_service_name, a.location_id, s.title AS service_title, ca.id as ca_id' )
                ->leftJoin( 'Appointment', 'a', 'a.id = ca.appointment_id' )
                ->leftJoin( 'Service', 's', 's.id = COALESCE(ca.compound_service_id, ca.collaborative_service_id, a.service_id)' )
                ->leftJoin( 'Order', 'o', 'o.id = ca.order_id', '\Bookly\Lib\Entities' )
                ->where( 'ca.order_id', $userData->getOrderId() )
                ->groupBy( 'COALESCE(ca.compound_token, ca.collaborative_token, ca.id)' )
                ->fetchArray();
            $description_template = Lib\Utils\Codes::getICSDescriptionTemplate();
            foreach ( $records as $appointment ) {
                if ( $appointment['start_date'] !== null ) {
                    $item = Simple::create( CustomerAppointment::find( $appointment['ca_id'] ) );
                    $item->getAppointment()
                        ->setStartDate( $appointment['start_date'] )
                        ->setEndDate( $appointment['end_date'] );
                    $description_codes = Lib\Utils\Codes::getICSCodes( $item );
                    if ( $appointment['service_id'] === null ) {
                        $service_name = $appointment['custom_service_name'];
                    } else {
                        $service_name = Common::getTranslatedString( 'service_' . $appointment['service_id'], $appointment['service_title'] );
                    }

                    $ics->addEvent( $appointment['start_date'], $appointment['end_date'], $service_name, Lib\Utils\Codes::replace( $description_template, $description_codes, false ), $appointment['location_id'] );
                }
            }
        }

        return $ics;
    }

    /**
     * @return Event[]
     */
    public function getEvents()
    {
        return $this->events;
    }
}