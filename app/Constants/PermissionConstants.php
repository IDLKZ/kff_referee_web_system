<?php

namespace App\Constants;

class PermissionConstants
{
    // Действия
    const ACTION_INDEX = 'index';
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    // Roles
    const ROLES_INDEX = 'roles.index';
    const ROLES_CREATE = 'roles.create';
    const ROLES_UPDATE = 'roles.update';
    const ROLES_DELETE = 'roles.delete';

    // Permissions
    const PERMISSIONS_INDEX = 'permissions.index';
    const PERMISSIONS_CREATE = 'permissions.create';
    const PERMISSIONS_UPDATE = 'permissions.update';
    const PERMISSIONS_DELETE = 'permissions.delete';

    // Role Permissions
    const ROLE_PERMISSIONS_INDEX = 'role_permissions.index';
    const ROLE_PERMISSIONS_CREATE = 'role_permissions.create';
    const ROLE_PERMISSIONS_UPDATE = 'role_permissions.update';
    const ROLE_PERMISSIONS_DELETE = 'role_permissions.delete';

    // Files
    const FILES_INDEX = 'files.index';
    const FILES_CREATE = 'files.create';
    const FILES_UPDATE = 'files.update';
    const FILES_DELETE = 'files.delete';

    // Users
    const USERS_INDEX = 'users.index';
    const USERS_CREATE = 'users.create';
    const USERS_UPDATE = 'users.update';
    const USERS_DELETE = 'users.delete';

    // Countries
    const COUNTRIES_INDEX = 'countries.index';
    const COUNTRIES_CREATE = 'countries.create';
    const COUNTRIES_UPDATE = 'countries.update';
    const COUNTRIES_DELETE = 'countries.delete';

    // Cities
    const CITIES_INDEX = 'cities.index';
    const CITIES_CREATE = 'cities.create';
    const CITIES_UPDATE = 'cities.update';
    const CITIES_DELETE = 'cities.delete';

    // Judge Cities
    const JUDGE_CITIES_INDEX = 'judge_cities.index';
    const JUDGE_CITIES_CREATE = 'judge_cities.create';
    const JUDGE_CITIES_UPDATE = 'judge_cities.update';
    const JUDGE_CITIES_DELETE = 'judge_cities.delete';

    // Judge Types
    const JUDGE_TYPES_INDEX = 'judge_types.index';
    const JUDGE_TYPES_CREATE = 'judge_types.create';
    const JUDGE_TYPES_UPDATE = 'judge_types.update';
    const JUDGE_TYPES_DELETE = 'judge_types.delete';

    // Tournaments
    const TOURNAMENTS_INDEX = 'tournaments.index';
    const TOURNAMENTS_CREATE = 'tournaments.create';
    const TOURNAMENTS_UPDATE = 'tournaments.update';
    const TOURNAMENTS_DELETE = 'tournaments.delete';

    // Seasons
    const SEASONS_INDEX = 'seasons.index';
    const SEASONS_CREATE = 'seasons.create';
    const SEASONS_UPDATE = 'seasons.update';
    const SEASONS_DELETE = 'seasons.delete';

    // Club Types
    const CLUB_TYPES_INDEX = 'club_types.index';
    const CLUB_TYPES_CREATE = 'club_types.create';
    const CLUB_TYPES_UPDATE = 'club_types.update';
    const CLUB_TYPES_DELETE = 'club_types.delete';

    // Clubs
    const CLUBS_INDEX = 'clubs.index';
    const CLUBS_CREATE = 'clubs.create';
    const CLUBS_UPDATE = 'clubs.update';
    const CLUBS_DELETE = 'clubs.delete';

    // Stadiums
    const STADIUMS_INDEX = 'stadiums.index';
    const STADIUMS_CREATE = 'stadiums.create';
    const STADIUMS_UPDATE = 'stadiums.update';
    const STADIUMS_DELETE = 'stadiums.delete';

    // Club Stadiums
    const CLUB_STADIUMS_INDEX = 'club_stadiums.index';
    const CLUB_STADIUMS_CREATE = 'club_stadiums.create';
    const CLUB_STADIUMS_UPDATE = 'club_stadiums.update';
    const CLUB_STADIUMS_DELETE = 'club_stadiums.delete';

    // Hotels
    const HOTELS_INDEX = 'hotels.index';
    const HOTELS_CREATE = 'hotels.create';
    const HOTELS_UPDATE = 'hotels.update';
    const HOTELS_DELETE = 'hotels.delete';

    // Hotel Rooms
    const HOTEL_ROOMS_INDEX = 'hotel_rooms.index';
    const HOTEL_ROOMS_CREATE = 'hotel_rooms.create';
    const HOTEL_ROOMS_UPDATE = 'hotel_rooms.update';
    const HOTEL_ROOMS_DELETE = 'hotel_rooms.delete';

    // Facilities
    const FACILITIES_INDEX = 'facilities.index';
    const FACILITIES_CREATE = 'facilities.create';
    const FACILITIES_UPDATE = 'facilities.update';
    const FACILITIES_DELETE = 'facilities.delete';

    // Room Facilities
    const ROOM_FACILITIES_INDEX = 'room_facilities.index';
    const ROOM_FACILITIES_CREATE = 'room_facilities.create';
    const ROOM_FACILITIES_UPDATE = 'room_facilities.update';
    const ROOM_FACILITIES_DELETE = 'room_facilities.delete';

    // Category Operations
    const CATEGORY_OPERATIONS_INDEX = 'category_operations.index';
    const CATEGORY_OPERATIONS_CREATE = 'category_operations.create';
    const CATEGORY_OPERATIONS_UPDATE = 'category_operations.update';
    const CATEGORY_OPERATIONS_DELETE = 'category_operations.delete';

    // Operations
    const OPERATIONS_INDEX = 'operations.index';
    const OPERATIONS_CREATE = 'operations.create';
    const OPERATIONS_UPDATE = 'operations.update';
    const OPERATIONS_DELETE = 'operations.delete';

    // Role Operations
    const ROLE_OPERATIONS_INDEX = 'role_operations.index';
    const ROLE_OPERATIONS_CREATE = 'role_operations.create';
    const ROLE_OPERATIONS_UPDATE = 'role_operations.update';
    const ROLE_OPERATIONS_DELETE = 'role_operations.delete';

    // Matches
    const MATCHES_INDEX = 'matches.index';
    const MATCHES_CREATE = 'matches.create';
    const MATCHES_UPDATE = 'matches.update';
    const MATCHES_DELETE = 'matches.delete';

    // Match Judges
    const MATCH_JUDGES_INDEX = 'match_judges.index';
    const MATCH_JUDGES_CREATE = 'match_judges.create';
    const MATCH_JUDGES_UPDATE = 'match_judges.update';
    const MATCH_JUDGES_DELETE = 'match_judges.delete';

    // Judge Requirements
    const JUDGE_REQUIREMENTS_INDEX = 'judge_requirements.index';
    const JUDGE_REQUIREMENTS_CREATE = 'judge_requirements.create';
    const JUDGE_REQUIREMENTS_UPDATE = 'judge_requirements.update';
    const JUDGE_REQUIREMENTS_DELETE = 'judge_requirements.delete';

    // Match Logists
    const MATCH_LOGISTS_INDEX = 'match_logists.index';
    const MATCH_LOGISTS_CREATE = 'match_logists.create';
    const MATCH_LOGISTS_UPDATE = 'match_logists.update';
    const MATCH_LOGISTS_DELETE = 'match_logists.delete';

    // Transport Types
    const TRANSPORT_TYPES_INDEX = 'transport_types.index';
    const TRANSPORT_TYPES_CREATE = 'transport_types.create';
    const TRANSPORT_TYPES_UPDATE = 'transport_types.update';
    const TRANSPORT_TYPES_DELETE = 'transport_types.delete';

    // Trips
    const TRIPS_INDEX = 'trips.index';
    const TRIPS_CREATE = 'trips.create';
    const TRIPS_UPDATE = 'trips.update';
    const TRIPS_DELETE = 'trips.delete';

    // Trip Hotels
    const TRIP_HOTELS_INDEX = 'trip_hotels.index';
    const TRIP_HOTELS_CREATE = 'trip_hotels.create';
    const TRIP_HOTELS_UPDATE = 'trip_hotels.update';
    const TRIP_HOTELS_DELETE = 'trip_hotels.delete';

    // Trip Migrations
    const TRIP_MIGRATIONS_INDEX = 'trip_migrations.index';
    const TRIP_MIGRATIONS_CREATE = 'trip_migrations.create';
    const TRIP_MIGRATIONS_UPDATE = 'trip_migrations.update';
    const TRIP_MIGRATIONS_DELETE = 'trip_migrations.delete';

    // Trip Documents
    const TRIP_DOCUMENTS_INDEX = 'trip_documents.index';
    const TRIP_DOCUMENTS_CREATE = 'trip_documents.create';
    const TRIP_DOCUMENTS_UPDATE = 'trip_documents.update';
    const TRIP_DOCUMENTS_DELETE = 'trip_documents.delete';

    // Match Reports
    const MATCH_REPORTS_INDEX = 'match_reports.index';
    const MATCH_REPORTS_CREATE = 'match_reports.create';
    const MATCH_REPORTS_UPDATE = 'match_reports.update';
    const MATCH_REPORTS_DELETE = 'match_reports.delete';

    // Match Protocol Requirements
    const MATCH_PROTOCOL_REQUIREMENTS_INDEX = 'match_protocol_requirements.index';
    const MATCH_PROTOCOL_REQUIREMENTS_CREATE = 'match_protocol_requirements.create';
    const MATCH_PROTOCOL_REQUIREMENTS_UPDATE = 'match_protocol_requirements.update';
    const MATCH_PROTOCOL_REQUIREMENTS_DELETE = 'match_protocol_requirements.delete';

    // Match Report Documents
    const MATCH_REPORT_DOCUMENTS_INDEX = 'match_report_documents.index';
    const MATCH_REPORT_DOCUMENTS_CREATE = 'match_report_documents.create';
    const MATCH_REPORT_DOCUMENTS_UPDATE = 'match_report_documents.update';
    const MATCH_REPORT_DOCUMENTS_DELETE = 'match_report_documents.delete';

    // Match Operation Logs
    const MATCH_OPERATION_LOGS_INDEX = 'match_operation_logs.index';
    const MATCH_OPERATION_LOGS_CREATE = 'match_operation_logs.create';
    const MATCH_OPERATION_LOGS_UPDATE = 'match_operation_logs.update';
    const MATCH_OPERATION_LOGS_DELETE = 'match_operation_logs.delete';

    // Notifications
    const NOTIFICATIONS_INDEX = 'notifications.index';
    const NOTIFICATIONS_CREATE = 'notifications.create';
    const NOTIFICATIONS_UPDATE = 'notifications.update';
    const NOTIFICATIONS_DELETE = 'notifications.delete';

    /**
     * Все действия
     */
    public static function actions(): array
    {
        return [
            self::ACTION_INDEX,
            self::ACTION_CREATE,
            self::ACTION_UPDATE,
            self::ACTION_DELETE,
        ];
    }

    /**
     * Все таблицы с переводами названий
     *
     * @return array<string, array{ru: string, kk: string, en: string}>
     */
    public static function tables(): array
    {
        return [
            'roles' => ['ru' => 'Роли', 'kk' => 'Рөлдер', 'en' => 'Roles'],
            'permissions' => ['ru' => 'Разрешения', 'kk' => 'Рұқсаттар', 'en' => 'Permissions'],
            'role_permissions' => ['ru' => 'Связи ролей и разрешений', 'kk' => 'Рөлдер мен рұқсаттар байланысы', 'en' => 'Role permissions'],
            'files' => ['ru' => 'Файлы', 'kk' => 'Файлдар', 'en' => 'Files'],
            'users' => ['ru' => 'Пользователи', 'kk' => 'Пайдаланушылар', 'en' => 'Users'],
            'countries' => ['ru' => 'Страны', 'kk' => 'Елдер', 'en' => 'Countries'],
            'cities' => ['ru' => 'Города', 'kk' => 'Қалалар', 'en' => 'Cities'],
            'judge_cities' => ['ru' => 'Города судей', 'kk' => 'Төрешілер қалалары', 'en' => 'Judge cities'],
            'judge_types' => ['ru' => 'Типы судей', 'kk' => 'Төрешілер түрлері', 'en' => 'Judge types'],
            'tournaments' => ['ru' => 'Турниры', 'kk' => 'Турнирлер', 'en' => 'Tournaments'],
            'seasons' => ['ru' => 'Сезоны', 'kk' => 'Маусымдар', 'en' => 'Seasons'],
            'club_types' => ['ru' => 'Типы клубов', 'kk' => 'Клуб түрлері', 'en' => 'Club types'],
            'clubs' => ['ru' => 'Клубы', 'kk' => 'Клубтар', 'en' => 'Clubs'],
            'stadiums' => ['ru' => 'Стадионы', 'kk' => 'Стадиондар', 'en' => 'Stadiums'],
            'club_stadiums' => ['ru' => 'Стадионы клубов', 'kk' => 'Клуб стадиондары', 'en' => 'Club stadiums'],
            'hotels' => ['ru' => 'Гостиницы', 'kk' => 'Қонақүйлер', 'en' => 'Hotels'],
            'hotel_rooms' => ['ru' => 'Номера гостиниц', 'kk' => 'Қонақүй бөлмелері', 'en' => 'Hotel rooms'],
            'facilities' => ['ru' => 'Удобства', 'kk' => 'Ыңғайлылықтар', 'en' => 'Facilities'],
            'room_facilities' => ['ru' => 'Удобства номеров', 'kk' => 'Бөлме ыңғайлылықтары', 'en' => 'Room facilities'],
            'category_operations' => ['ru' => 'Категории операций', 'kk' => 'Операция санаттары', 'en' => 'Operation categories'],
            'operations' => ['ru' => 'Операции', 'kk' => 'Операциялар', 'en' => 'Operations'],
            'role_operations' => ['ru' => 'Операции ролей', 'kk' => 'Рөл операциялары', 'en' => 'Role operations'],
            'matches' => ['ru' => 'Матчи', 'kk' => 'Матчтар', 'en' => 'Matches'],
            'match_judges' => ['ru' => 'Судьи матчей', 'kk' => 'Матч төрешілері', 'en' => 'Match judges'],
            'judge_requirements' => ['ru' => 'Требования к судьям', 'kk' => 'Төрешілерге талаптар', 'en' => 'Judge requirements'],
            'match_logists' => ['ru' => 'Логисты матчей', 'kk' => 'Матч логистері', 'en' => 'Match logists'],
            'transport_types' => ['ru' => 'Типы транспорта', 'kk' => 'Көлік түрлері', 'en' => 'Transport types'],
            'trips' => ['ru' => 'Командировки', 'kk' => 'Іссапарлар', 'en' => 'Trips'],
            'trip_hotels' => ['ru' => 'Гостиницы командировок', 'kk' => 'Іссапар қонақүйлері', 'en' => 'Trip hotels'],
            'trip_migrations' => ['ru' => 'Переезды командировок', 'kk' => 'Іссапар көшулері', 'en' => 'Trip migrations'],
            'trip_documents' => ['ru' => 'Документы командировок', 'kk' => 'Іссапар құжаттары', 'en' => 'Trip documents'],
            'match_reports' => ['ru' => 'Отчёты матчей', 'kk' => 'Матч есептері', 'en' => 'Match reports'],
            'match_protocol_requirements' => ['ru' => 'Требования протоколов матчей', 'kk' => 'Матч хаттамаларының талаптары', 'en' => 'Match protocol requirements'],
            'match_report_documents' => ['ru' => 'Документы отчётов матчей', 'kk' => 'Матч есеп құжаттары', 'en' => 'Match report documents'],
            'match_operation_logs' => ['ru' => 'Журнал операций матчей', 'kk' => 'Матч операциялар журналы', 'en' => 'Match operation logs'],
            'notifications' => ['ru' => 'Уведомления', 'kk' => 'Хабарламалар', 'en' => 'Notifications'],
        ];
    }

    /**
     * Переводы действий
     *
     * @return array<string, array{ru: string, kk: string, en: string}>
     */
    public static function actionTranslations(): array
    {
        return [
            self::ACTION_INDEX => ['ru' => 'Просмотр', 'kk' => 'Қарау', 'en' => 'View'],
            self::ACTION_CREATE => ['ru' => 'Создание', 'kk' => 'Құру', 'en' => 'Create'],
            self::ACTION_UPDATE => ['ru' => 'Редактирование', 'kk' => 'Өңдеу', 'en' => 'Update'],
            self::ACTION_DELETE => ['ru' => 'Удаление', 'kk' => 'Жою', 'en' => 'Delete'],
        ];
    }

    /**
     * Получить все разрешения (value)
     *
     * @return array
     */
    public static function all(): array
    {
        $permissions = [];
        foreach (array_keys(self::tables()) as $table) {
            foreach (self::actions() as $action) {
                $permissions[] = "{$table}.{$action}";
            }
        }
        return $permissions;
    }
}
