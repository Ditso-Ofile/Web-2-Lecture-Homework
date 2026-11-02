<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TxtSeeder extends Seeder
{
    private function readTxt(string $file): array
    {
        $lines = file(database_path("data/$file"), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        array_shift($lines); // skip header
        $rows = [];
        foreach ($lines as $line) {
            if (!mb_check_encoding($line, 'UTF-8')) {
                $line = mb_convert_encoding($line, 'UTF-8', 'Windows-1250');
            }
            $rows[] = array_map('trim', explode("\t", $line));
        }
        return $rows;
    }

    private function num($v) { return ($v === '' || $v === null) ? null : (int)$v; }

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('populations')->truncate();
        DB::table('towns')->truncate();
        DB::table('counties')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        foreach ($this->readTxt('counties.txt') as $r) {
            DB::table('counties')->insert(['id' => $r[0], 'cname' => $r[1]]);
        }

        foreach (array_chunk($this->readTxt('towns.txt'), 500) as $chunk) {
            DB::table('towns')->insert(array_map(fn($r) => [
                'id'          => $r[0],
                'tname'       => $r[1],
                'countyid'    => $r[2],
                'countyseat'  => $r[3] != '0',
                'countylevel' => $r[4] != '0',
            ], $chunk));
        }

        foreach (array_chunk($this->readTxt('populations.txt'), 500) as $chunk) {
            DB::table('populations')->insert(array_map(fn($r) => [
                'townid' => $r[0],
                'ryear'  => $r[1],
                'women'  => $this->num($r[2] ?? null),
                'total'  => $this->num($r[3] ?? null),
            ], $chunk));
        }
    }
}