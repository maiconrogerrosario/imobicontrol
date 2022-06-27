<?php
ob_start();

require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;
use Source\Core\Session;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/**
 * WEB ROUTES
 */
$route->group(null);
$route->get("/", "Web:home");


//auth
$route->group(null);
$route->get("/entrar", "Web:login");
$route->post("/entrar", "Web:login");
$route->get("/cadastrar", "Web:register");
$route->post("/cadastrar", "Web:register");
$route->get("/recuperar", "Web:forget");
$route->post("/recuperar", "Web:forget");
$route->get("/recuperar/{code}", "Web:reset");
$route->post("/recuperar/resetar", "Web:reset");

//auth-worl
$route->group(null);
$route->get("/work-entrar", "Web:workLogin");
$route->post("/work-entrar", "Web:workLogin");
$route->get("/work-cadastrar", "Web:workRegister");
$route->post("/work-cadastrar", "Web:workRegister");
$route->get("/work-recuperar", "Web:workForget");
$route->post("/work-recuperar", "Web:workForget");
$route->get("/work-recuperar/{code}", "Web:workReset");
$route->post("/work-recuperar/resetar", "Web:workReset");

//optin
$route->group(null);
$route->get("/confirma", "Web:confirm");
$route->get("/obrigado/{email}", "Web:success");

//**----------------------------------------------------------------------**//
//**-----------Software Gerenciamento de Imobiliaria-----------------**//


$route->group("/work");
$route->get("/", "Work:home");

/** Start Logout Work*/
$route->get("/work-sair", "Work:workLogout");
/** End Logout Work*/

/** Start User*/
$route->get("/user", "Work:user");
$route->post("/user", "Work:user");
$route->get("/user/{user_id}", "Work:user");
$route->post("/user/{user_id}", "Work:user");
$route->get("/user/all/{page}", "Work:user");
$route->get("/user-add", "Work:userAdd");
$route->post("/user-add", "Work:userAdd");
$route->get("/user-edit/{id}", "Work:userEdit");
$route->post("/user-edit/{id}", "Work:userEdit");
/** End User*/

/**Start Customers */
$route->get("/customer", "Work:customer");
$route->post("/customer", "Work:customer");
$route->get("/customer/all/{page}", "Work:customer");

$route->get("/customer-add", "Work:customerAdd");
$route->post("/customer-add", "Work:customerAdd");
$route->get("/customer-edit/{customer_id}", "Work:customerEdit");
$route->post("/customer-edit/{customer_id}", "Work:customerEdit");
$route->get("/customer-delete/{customer_id}", "Work:customer");
$route->post("/customer-delete/{customer_id}", "Work:customer");
/**End Clientes*/

/**Start owner */
$route->get("/owner", "Work:owner");
$route->post("/owner", "Work:owner");
$route->get("/owner-add", "Work:ownerAdd");
$route->post("/owner-add", "Work:ownerAdd");
$route->get("/owner-edit/{owner_id}", "Work:ownerEdit");
$route->post("/owner-edit/{owner_id}", "Work:ownerEdit");
$route->get("/owner-delete/{owner_id}", "Work:owner");
$route->post("/owner-delete/{owner_id}", "Work:owner");
/**End owner*/


/**Start property */
$route->get("/property", "Work:property");
$route->post("/property", "Work:property");
$route->get("/property-add", "Work:propertyAdd");
$route->post("/property-add", "Work:propertyAdd");
$route->get("/property-edit/{property_id}", "Work:propertyEdit");
$route->post("/property-edit/{property_id}", "Work:propertyEdit");
$route->get("/property-delete/{property_id}", "Work:property");
$route->post("/property-delete/{property_id}", "Work:property");
/**End property*/


/**Start contract */
$route->get("/contract", "Work:contract");
$route->post("/contract", "Work:contract");
$route->get("/contract-add", "Work:contractAdd");
$route->post("/contract-add", "Work:contractAdd");
$route->get("/contract-edit/{contract_id}", "Work:contractEdit");
$route->post("/contract-edit/{contract_id}", "Work:contractEdit");
$route->get("/contract-delete/{contract_id}", "Work:contract");
$route->post("/contract-delete/{contract_id}", "Work:contract");
/**End contract*/

/** Start Financeiro*/
$route->get("/receber", "Work:income");
$route->get("/receber/{status}/{category}/{date}", "Work:income");
$route->get("/pagar", "Work:expense");
$route->get("/pagar/{status}/{category}/{date}", "Work:expense");
$route->get("/faturas", "Work:invoices");
$route->get("/faturas/{id}", "Work:invoices");
$route->get("/faturas/{type}/{category}/{dateinitial}/{datefinal}", "Work:invoices");
$route->get("/faturas/{type}/{category}/{dateinitial}/{datefinal}/{page}", "Work:invoices");
$route->get("/faturas/{id}/{type}/{category}/{dateinitial}/{datefinal}", "Work:invoices");
$route->get("/faturas/{id}/{type}/{category}/{dateinitial}/{datefinal}/{page}", "Work:invoices");

$route->get("/relatorio/{type}/{category}/{dateinitial}/{datefinal}", "Work:relatorio");
$route->post("/relatorio/{type}/{category}/{dateinitial}/{datefinal}", "Work:relatorio");
$route->get("/relatorio/{id}/{type}/{category}/{dateinitial}/{datefinal}", "Work:relatorio");
$route->post("/relatorio/{id}/{type}/{category}/{dateinitial}/{datefinal}", "Work:relatorio");

$route->post("/invoicefilter", "Work:invoiceFilter");
$route->post("/invoicefilter/{id}", "Work:invoiceFilter");

$route->get("/fixas", "Work:fixed");
$route->get("/carteiras", "Work:wallets");
$route->get("/fatura/{invoice}", "Work:invoice");
$route->get("/perfil", "Work:profile");
$route->get("/assinatura", "Work:signature");
$route->get("/sair", "Work:logout");
$route->post("/dash", "Work:dash");
$route->post("/atualizar/{id}", "Work:atualizar");
$route->post("/launch", "Work:launch");
$route->post("/invoice/{invoice}", "Work:invoice");
$route->post("/remove/{invoice}", "Work:remove");
$route->post("/support", "Work:support");
$route->post("/onpaid", "Work:onpaid");
$route->post("/onpaidincome", "Work:onpaidincome");
$route->post("/onpaidexpense", "Work:onpaidexpense");
$route->post("/filter", "Work:filter");
$route->post("/profile", "Work:profile");
$route->post("/wallets/{wallet}", "Work:wallets");

$route->post("/wallet-edit/{id}", "Work:walletEdit");
$route->get("/wallet-edit/{id}", "Work:walletEdit");

$route->get("/carteirasinativas", "Work:walletsInactive");
$route->post("/carteirasinativas", "Work:walletsInactive");
$route->get("/carteirasinativas/all/{page}", "Work:walletsInactive");


/** End Financeiro*/
$route->get("/balançofinanceiro", "Work:BalanceByPeriodMonthYear");
$route->get("/balançofinanceiro/{dateinitial}/{datefinal}", "Work:BalanceByPeriodMonthYear");
$route->post("/filterPeriodMonthYear", "Work:filterPeriodMonthYear");
$route->get("/relatoriobalançofinanceiro/{dateinitial}/{datefinal}", "Work:BalanceByPeriodMonthYearReport");
$route->post("/relatoriobalançofinanceiro/{dateinitial}/{datefinal}", "Work:BalanceByPeriodMonthYearReport");

/** Start Category*/
$route->get("/category", "Work:Category");
$route->post("/category", "Work:Category");
$route->get("/category/all/{page}", "Work:Category");
$route->get("/category-add", "Work:CategoryAdd");
$route->post("/category-add", "Work:CategoryAdd");
$route->get("/category-delete/{id}", "Work:Category");
$route->post("/category-delete/{id}", "Work:Category");
/** End Category*/

/** Start Relátorios*/
$route->get("/relatorio2/{wallet}/{category}/{dateinitial}/{datefinal}", "Work:relatorio2");
$route->post("/relatorio2/{wallet}/{category}/{dateinitial}/{datefinal}", "Work:relatorio2");
$route->get("/fluxodecaixa", "Work:cashFlow");
$route->get("/fluxodecaixa/{dateinitial}/{datefinal}", "Work:cashFlow");
$route->post("/balanceFilter", "Work:balanceFilter");
$route->get("/fluxodecaixarelatorio/{dateinitial}/{datefinal}", "Work:cashFlowReport");
$route->post("/fluxodecaixarelatorio/{dateinitial}/{datefinal}", "Work:cashFlowReport");


/** End Financeiro*/



//**----------------------------------------------------------------------**//
//**-----------Software Gerenciamento de Obras e Serviços-----------------**//



/**
 * ERROR ROUTES
 */
$route->group("/ops");
$route->get("/{errcode}", "Web:error");

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();



