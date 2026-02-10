<?php

namespace App\Constants;

class RoleConstants
{
    // Роли системы
    const ADMINISTRATOR = 'administrator';
    const REFEREEING_DEPARTMENT_EMPLOYEE = 'refereeing_department_employee';
    const REFEREEING_DEPARTMENT_HEAD = 'refereeing_department_head';
    const FINANCE_DEPARTMENT_SPECIALIST = 'finance_department_specialist';
    const FINANCE_DEPARTMENT_HEAD = 'finance_department_head';
    const SOCCER_REFEREE = 'soccer_referee';
    const REFEREEING_DEPARTMENT_LOGISTICIAN = 'refereeing_department_logistician';
    const REFEREEING_DEPARTMENT_ACCOUNTANT = 'refereeing_department_accountant';

    const ADMINISTRATOR_GROUP = 'administrator_group';
    const KFF_PFLK_GROUP = 'kff_pflk_group';
    const REFEREE_GROUP = 'judge_group';

    /**
     * Получить все роли
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::ADMINISTRATOR,
            self::REFEREEING_DEPARTMENT_EMPLOYEE,
            self::REFEREEING_DEPARTMENT_HEAD,
            self::FINANCE_DEPARTMENT_SPECIALIST,
            self::FINANCE_DEPARTMENT_HEAD,
            self::SOCCER_REFEREE,
            self::REFEREEING_DEPARTMENT_LOGISTICIAN,
            self::REFEREEING_DEPARTMENT_ACCOUNTANT,
        ];
    }

    /**
     * Получить административные роли
     *
     * @return array
     */
    public static function administrative(): array
    {
        return [
            self::ADMINISTRATOR,
            self::REFEREEING_DEPARTMENT_HEAD,
            self::FINANCE_DEPARTMENT_HEAD,
        ];
    }
}
