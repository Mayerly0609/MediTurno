<?php

namespace App\Services;

use SoapClient;
use SoapFault;

class UnoeeSoapService
{
    public function importarXml(string $parametros)
    {
        ini_set('default_socket_timeout', 600);

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

        $options = [
            'soap_version' => SOAP_1_2,
            'trace' => 1,
            'exceptions' => 1,
            'stream_context' => $context,
        ];

        try {

            $soapClient = new SoapClient(
                config('unoee.wsdl_url'),
                $options
            );

            $result = $soapClient->ImportarXML([
                'pvstrDatos' => $parametros,
                'printTipoError' => 1,
            ]);

            return [
                'success' => true,
                'result' => $result,
                'xml_enviado' => $parametros,
            ];

        } catch (SoapFault $e) {

            return 'SOAP Fault: ' . $e->getMessage();

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }


    public function generarXml(string $user,string $pass){
        $parametros = "<Importar>";
        $parametros .= "<NombreConexion>"
            . config('unoee.database')
            . "</NombreConexion>\n";

        $parametros .= "<IdCia>"
            . config('unoee.company')
            . "</IdCia>\n";

        $parametros .= "<Usuario>"
            . $user
            . "</Usuario>\n";

        $parametros .= "<Clave>"
            . $pass
            . "</Clave>\n";

        $parametros .= "<Datos>\n";

        $parametros .= "<Linea>000000100000001"
            . str_pad(config('unoee.company'), 3, '0', STR_PAD_LEFT)
            . "</Linea>\n";

        $parametros .= "<Linea>00000020412000500500000009".str_repeat(' ', 55)."999  C010AB50  NACIONAL       000000000300000000015.000000000000000.00000000011".str_repeat(' ', 160)."0000000000</Linea>";

        $parametros .= "<Linea>"
            . str_pad('3', 7, '0', STR_PAD_LEFT)
            . "99990001"
            . str_pad(config('unoee.company'), 3, '0', STR_PAD_LEFT)
            . "</Linea>\n";

        $parametros .= "</Datos>\n";
        $parametros .= "</Importar>\n";

        return  $this->importarXml($parametros);
    }
}