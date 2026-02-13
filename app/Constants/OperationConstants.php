<?php

namespace App\Constants;

class OperationConstants
{
    // Назначение судей
    const MATCH_CREATED_WAITING_REFEREES = 'match_created_waiting_referees';
    const REFEREE_ASSIGNMENT = 'referee_assignment';
    const REFEREE_TEAM_APPROVAL = 'referee_team_approval';
    const REFEREE_REASSIGNMENT = 'referee_reassignment';

    // Командировка
    const SELECT_TRANSPORT_DEPARTURE = 'select_transport_departure';
    const TRIP_PROCESSING = 'trip_processing';

    // Протокол
    const WAITING_FOR_PROTOCOL = 'waiting_for_protocol';
    const PROTOCOL_REVIEW = 'protocol_review';
    const PROTOCOL_REPROCESSING = 'protocol_reprocessing';

    // Завершение
    const SUCCESSFULLY_COMPLETED = 'successfully_completed';
}
