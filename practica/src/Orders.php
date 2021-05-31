<?php 

class Orders
{
    private $host;
    private $dbname;
    private $user;
    private $password;

    //BBDD
    public function __construct()
    {
        $this->host = 'localhost';
        $this->dbname = 'icb0006_uf4_pr01';
        $this->user = 'root';
        $this->password = '';
    }

    //per poder-se connectar al localhost
    private function connect()
    {
        mysqli_report(MYSQLI_REPORT_STRICT);
        try {
            $conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
            return $conn;
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }
    public function getOrders()
    {
        //si hi ha el domain o no
        if (isset($_GET['domain'])) {
            $dom = $_GET['domain'];
            //Es per comprovar correctament si es un correo valid o no, sino ho es retorna un missatge.
            if ($dom == "yahoo" || $dom == "hotmail" || $dom == "gmail") {
                //guardo la query connectan-se al localhost a la variable result
                $result = $this->connect()->query('SELECT * FROM orders Where company like "%' . $dom . '%" ;');
                //Agrego un array()
                $array['register'] = array();
                //si el count es 0 retorna un json i si es diferent de 0 guarda al final del array, amb el array_push
                if ($result->num_rows != 0) {
                    while ($row = $result->fetch_assoc()) {
                        $register = array(
                            'id_order' => $row['id_order'],
                            'date' => $row['date'],
                            'company' => $row['company'],
                            'qty' => $row['qty'],
                        );
                        array_push($array['register'], $register);
                    }
                }
                return json_encode($array, JSON_FORCE_OBJECT);
            }
            return json_encode(['Missatge' => 'No hi ha dades'], JSON_FORCE_OBJECT);
            //si hi ha el date o no
        } elseif (isset($_GET['date'])) {
            //guardo a la variable date
            $date = $_GET['date'];
            //per la data
            $format = 'd-m-Y';
            //creo una variable en el qüal es guarda una cadena de temps 
            $d = DateTime::createFromFormat($format, $date);
            //si el $d o el $d amb el format 'd-m-y' es identic que $date
            //si es correcte entra i depenen si es diferent de 0 o no, retorna una cosa o una altre
            //si no es correcte retorna un missatge
            if ($d && $d->format($format) === $date) {
                $result = $this->connect()->query('SELECT * FROM orders Where date > ' . $date);
                $array['register'] = array();
                if ($result->num_rows != 0) {
                    while ($row = $result->fetch_assoc()) {
                        $register = array(
                            'id_order' => $row['id_order'],
                            'date' => $row['date'],
                            'company' => $row['company'],
                            'qty' => $row['qty'],
                        );
                        array_push($array['register'], $register);
                    }
                }
                return json_encode($array, JSON_FORCE_OBJECT);
            } else {
                return json_encode(['Missatge' => 'No hi ha dades'], JSON_FORCE_OBJECT);
            }
        } else {
            //guardo la query connectan-se al localhost a la variable result
            //el que fa es fer un select de tot el orders
            $result = $this->connect()->query('SELECT * FROM orders');
            $array['register'] = array();
            if ($result->num_rows != 0) {
                while ($row = $result->fetch_assoc()) {
                    $register = array(
                        'id_order' => $row['id_order'],
                        'date' => $row['date'],
                        'company' => $row['company'],
                        'qty' => $row['qty'],
                    );
                    array_push($array['register'], $register);
                }
            }
            return json_encode($array, JSON_FORCE_OBJECT);
        }
    }
}
