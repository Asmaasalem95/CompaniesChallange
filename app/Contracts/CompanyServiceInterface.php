<?php


namespace App\Contracts;


interface CompanyServiceInterface
{
    public function getCompaniesSymbolList() :array ;

    public function getFilteredCompanies(array  $filters);

}
