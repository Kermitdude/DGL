<?php

namespace DigitalGaming;

use DigitalGaming\Base\Setting as BaseSetting;

/**
 * Skeleton subclass for representing a row from the 'setting' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Setting extends BaseSetting
{
	
	public static function get($name)
	{
		$setting = Base\SettingQuery::create()->findOneByName($name);
		if (isset($setting)) return $setting->getValue();
		return false;
	}
	
	public static function add($name, $value)
    {
        $setting = new Setting();
		$setting->setName($name);
		$setting->setValue($value);
		$setting->save();
		return $setting;
	}
}

