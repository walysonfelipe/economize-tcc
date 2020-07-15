<?php
    use \Model\Admin;
    $sql = new Sql();

    if (Project::isXmlHttpRequest()) {
        $json = [];
        $json['status'] = 1;

        if (isset($_POST['id_atd'])) {
            $results = $sql->select("SELECT * FROM dados_atend_func WHERE atendimento_id = :atd_id AND funcionario_id = :func_id", [
                ":atd_id" => $_POST['id_atd'],
                ":func_id" => $_SESSION[Admin::SESSION]['funcionario_id']
            ]);

            if (count($results) == 0) {
                $stmt = $sql->query("INSERT INTO dados_atend_func(atendimento_id, funcionario_id) VALUES(:atd_id, :func_id)", [
                    ":atd_id" => $_POST['id_atd'],
                    ":func_id" => $_SESSION[Admin::SESSION]['funcionario_id']
                ]);

                if (!$stmt) {
                    $json['status'] = 0;
                }
            } else {
                $json['status'] = 0;
            }
        } elseif (isset($_POST['id_func'])) {
            $results = $sql->select("SELECT id_atd FROM atendimento");

            if (count($results) > 0) {
                foreach ($results as $v) {
                    $results2 = $sql->select("SELECT dados_id FROM dados_atend_func WHERE atendimento_id = :atd_id AND funcionario_id = :func_id", [
                        ":atd_id" => $v['id_atd'],
                        ":func_id" => $_SESSION[Admin::SESSION]['funcionario_id']
                    ]);

                    if (count($results2) == 0) {
                        $stmt = $sql->query("INSERT INTO dados_atend_func(atendimento_id, funcionario_id) VALUES(:atd_id, :func_id)", [
                            ":atd_id" => $v['id_atd'],
                            ":func_id" => $_SESSION[Admin::SESSION]['funcionario_id']
                        ]);

                        if (!$stmt) {
                            $json['status'] = 1;
                            break;
                        }
                    }
                }
            }
        } else {
            // Saída Datetime
            // object(DateInterval)[3]
            //     public 'y' => int 1
            //     public 'm' => int 0
            //     public 'd' => int 0
            //     public 'h' => int 0
            //     public 'i' => int 0
            //     public 's' => int 0
            //     public 'weekday' => int 0
            //     public 'weekday_behavior' => int 0
            //     public 'first_last_day_of' => int 0
            //     public 'invert' => int 0
            //     public 'days' => int 365
            //     public 'special_type' => int 0
            //     public 'special_amount' => int 0
            //     public 'have_weekday_relative' => int 0
            //     public 'have_special_relative' => int 0

            $json['noVisu'] = 0;
            $json['notificationVisu'] = [];
            $json['notificationNoVisu'] = [];

            $results = $sql->select("SELECT a.id_atd, a.nome_usu, a.dataenv_pro, ar.resp_id FROM dados_atend_func d JOIN atendimento a ON d.atendimento_id = a.id_atd LEFT JOIN atend_resposta ar ON ar.id_atd = a.id_atd WHERE d.funcionario_id = :func_id ORDER BY a.dataenv_pro DESC", [
                ":func_id" => $_SESSION[Admin::SESSION]['funcionario_id']
            ]);

            if (count($results) > 0) {
                foreach ($results as $v) {
                    $now = new DateTime();
                    $data_registro = new DateTime($v['dataenv_pro']);
                    $intervalo = $now->diff($data_registro);

                    if ($intervalo->d > 0) {
                        if ($intervalo->d == 1) {
                            $v['dataenv_pro'] = "Ontem às " . substr($v['dataenv_pro'],11,2) . "h" . substr($v['dataenv_pro'],14,2);
                        } else {
                            $v['dataenv_pro'] = substr($v['dataenv_pro'],8,2) . "/" . substr($v['dataenv_pro'],5,2) . "/" . substr($v['dataenv_pro'],0,4) . " às " . substr($v['dataenv_pro'],11,2) . "h" . substr($v['dataenv_pro'],-2);
                        }
                    } else {
                        if ($intervalo->h > 0) {
                            if ($intervalo->h == 1) {
                                $v['dataenv_pro'] = "Há " . $intervalo->h . " hora";
                            } else {
                                $v['dataenv_pro'] = "Há " . $intervalo->h . " horas";
                            }
                        } else {
                            if ($intervalo->i > 0) {
                                if ($intervalo->i == 1) {
                                    $v['dataenv_pro'] = "Há " . $intervalo->i . " minuto";
                                } else {
                                    $v['dataenv_pro'] = "Há " . $intervalo->i . " minutos";
                                }
                            } else {
                                if ($intervalo->s <= 15) {
                                    $v['dataenv_pro'] = "Agora mesmo";
                                } else {
                                    $v['dataenv_pro'] = "Há alguns instantes";
                                }
                            }
                        }
                    }

                    $json['notificationVisu'][] = $v;
                }
            }
            
            $results = $sql->select("SELECT a.id_atd, a.nome_usu, a.dataenv_pro, ar.resp_id FROM atendimento a LEFT JOIN atend_resposta ar ON ar.id_atd = a.id_atd ORDER BY a.dataenv_pro DESC");

            if (count($results) > 0) {
                foreach ($results as $v) {
                    $results2 = $sql->select("SELECT dados_id FROM dados_atend_func WHERE atendimento_id = :atd_id AND funcionario_id = :func_id", [
                        ":atd_id" => $v['id_atd'],
                        ":func_id" => $_SESSION[Admin::SESSION]['funcionario_id']
                    ]);

                    if (count($results2) == 0) {
                        $now = new DateTime();
                        $data_registro = new DateTime($v['dataenv_pro']);
                        $intervalo = $now->diff($data_registro);
        
                        if ($intervalo->d > 0) {
                            if ($intervalo->d == 1) {
                                $v['dataenv_pro'] = "Ontem às " . substr($v['dataenv_pro'],11,2) . "h" . substr($v['dataenv_pro'],14,2);
                            } else {
                                $v['dataenv_pro'] = substr($v['dataenv_pro'],8,2) . "/" . substr($v['dataenv_pro'],5,2) . "/" . substr($v['dataenv_pro'],0,4) . " às " . substr($v['dataenv_pro'],11,2) . "h" . substr($v['dataenv_pro'],-2);
                            }
                        } else {
                            if ($intervalo->h > 0) {
                                if ($intervalo->h == 1) {
                                    $v['dataenv_pro'] = "Há " . $intervalo->h . " hora";
                                } else {
                                    $v['dataenv_pro'] = "Há " . $intervalo->h . " horas";
                                }
                            } else {
                                if ($intervalo->i > 0) {
                                    if ($intervalo->i == 1) {
                                        $v['dataenv_pro'] = "Há " . $intervalo->i . " minuto";
                                    } else {
                                        $v['dataenv_pro'] = "Há " . $intervalo->i . " minutos";
                                    }
                                } else {
                                    if ($intervalo->s <= 15) {
                                        $v['dataenv_pro'] = "Agora mesmo";
                                    } else {
                                        $v['dataenv_pro'] = "Há alguns instantes";
                                    }
                                }
                            }
                        }
        
                        $json['noVisu']++;
                        $json['notificationNoVisu'][] = $v;
                    }
                }
            }

            $day = Date("d");
            $results = $sql->select("SELECT e.entrega_horario FROM entrega e JOIN compra c ON e.compra_id = c.compra_id WHERE c.status_id = 1 AND DAY(e.entrega_horario) = $day");
            if (count($results) > 0) {
                $json['entrega_pendente'] = 0;
                foreach ($results as $row) {
                    $regis = substr($row['entrega_horario'], 11, 2);

                    $hour = Date("H");
                    $hour = strtotime($hour, "-2 hour");
                    if ($hour <= $hour) {
                        $json['entrega_pendente']++;
                    }
                }
            }
        }

        echo json_encode($json);
    } else {
        require_once "__system__/404.php";
    }
