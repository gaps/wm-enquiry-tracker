<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $hk = Branch::create(array(
            "name" => "Hauz Khas"
        ));

        $pp = Branch::create(array(
            "name" => "Pitampua"
        ));

        $pv = Branch::create(array(
            "name" => "Preet Vihar"
        ));

        $sn = Branch::create(array(
            "name" => "Subhash Nagar"
        ));

        $noida = Branch::create(array(
            "name" => "Noida"
        ));

        $hkUser = User::create(array(
            'email' => 'hauzkhas@wisdommart.in',
            'password' => Hash::make('password')
        ));

        $noidaUser = User::create(array(
            'email' => 'noida@wisdommart.in',
            'password' => Hash::make('password')
        ));

        $pvUser = User::create(array(
            'email' => 'preetvihar@wisdommart.in',
            'password' => Hash::make('password')
        ));

        $ppUser = User::create(array(
            'email' => 'pitampura@wisdommart.in',
            'password' => Hash::make('password')
        ));

        $snUser = User::create(array(
            'email' => 'subhashnagar@wisdommart.in',
            'password' => Hash::make('password')
        ));

        $pankaj = User::create(array(
            'email' => 'pankaj@wisdommart.in',
            'password' => Hash::make('pass123')
        ));

        $laksh = User::create(array(
            'email' => 'lakshdeep@greenapplesolutions.com',
            'password' => Hash::make('pass123')
        ));

        $naveen = User::create(array(
            'email' => 'naveen@greenapplesolutions.com',
            'password' => Hash::make('pass123')
        ));

        $pv->users()->sync(array($pvUser->id, $pankaj->id, $laksh->id, $naveen->id));
        $hk->users()->sync(array($hkUser->id, $pankaj->id, $laksh->id, $naveen->id));
        $sn->users()->sync(array($snUser->id, $pankaj->id, $laksh->id, $naveen->id));
        $pp->users()->sync(array($ppUser->id, $pankaj->id, $laksh->id, $naveen->id));
        $noida->users()->sync(array($noidaUser->id, $pankaj->id, $laksh->id, $naveen->id));

    }


}