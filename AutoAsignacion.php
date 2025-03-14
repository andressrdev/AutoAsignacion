<?php
class AutoAsignacionPlugin extends MantisPlugin {

    function register() {
        $this->name        = 'AutoAsignacion';
        $this->description = 'Reasigna automáticamente el ticket si cumple con ciertas condiciones predefinidas.';
        $this->version = '1.1.0';
        $this->requires = array('MantisCore' => '2.0.0');
        $this->author = 'Andres Silva';
        $this->url = 'https://github.com/andressrdev/AutoAsignacion';
    }

    public function hooks() {
        return array(
            'EVENT_MENU_ISSUE' => 'mostrar_mensaje_ticket',
        );
    }

    public function mostrar_mensaje_ticket() {
        $bug_id = (int) $_GET['id'];

        if ($bug_id <= 0) {
            echo '<div class="plugin-message" style="padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; margin: 10px 0;">';
            echo 'El ID del ticket es inválido.';
            echo '</div>';
            return;
        }

        $bug = bug_get($bug_id);

        if ($bug) {
            $assigned_user_id = $bug->handler_id;
            $category_id = $bug->category_id;
            $assigned_user = user_get_name($assigned_user_id);
            $category_name = category_get_name($category_id);

            echo '<div class="plugin-message" style="padding: 10px; background-color: #f0f8ff; border: 1px solid #007bff; margin: 10px 0;">';
            echo '<strong>Asignado a:</strong> ' . $assigned_user . '<br>';
            echo '<strong>ID del Asignado:</strong> ' . $assigned_user_id . '<br>';
            echo '<strong>Categoría:</strong> ' . $category_name . '<br>';
            echo '<strong>ID de la Categoría:</strong> ' . $category_id . '<br>';
            echo '</div>';

            $nuevo_asignado = null;
            
            if ($category_id == 1906 && $assigned_user_id == 39) {
                $nuevo_asignado = 382;
            } elseif ($category_id == 1907 && $assigned_user_id == 39) {
                $nuevo_asignado = 462;
            }

            if ($nuevo_asignado !== null) {
                bug_assign($bug_id, $nuevo_asignado);

                echo '<div class="plugin-message" style="padding: 10px; background-color: #e7f3ff; border: 1px solid #33b5e5; margin: 10px 0;">';
                echo '<strong>El ticket ha sido reasignado a:</strong> ' . user_get_name($nuevo_asignado) . ' (ID: ' . $nuevo_asignado . ')';
                echo '</div>';
            }
        } else {
            echo '<div class="plugin-message" style="padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; margin: 10px 0;">';
            echo 'El ticket no fue encontrado o no existe.';
            echo '</div>';
        }
    }
}
?>
