<?php
namespace App\HTML;

class Form {
    private $data;
    private $errors;
    public function __construct($data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }
    public function input (string $key, string $label): string
    {
        $value = $this->getValue($key);
        $type = $key === "password" ? "password" : "text";
        return <<<HTML
            <div class="form-group">
                <label for="field{$key}">{$label}</label>
                <input type="{$type}" id="field{$key}" class="{$this->getInputClass($key)}" name="{$key}" value="{$value}" required>
                {$this->getErrorFeedback($key)}
            </div>
HTML;
    }
    public function textarea (string $key, string $label): string
    {
        $value = $this->getValue($key);

        return <<<HTML
            <div class="form-group">
                <label for="field{$key}">{$label}</label>
                <textarea type="text" id="field{$key}" class="{$this->getInputClass($key)}" name="{$key}" required>{$value}</textarea>
                {$this->getErrorFeedback($key)}
            </div>
HTML;
    }
    private function getValue(string $key)
    {
        if (is_array($this->data)) {
            return $this->data[$key] ?? null;
        }

        $method = 'get' . ucfirst($key);
        $value = $this->data->$method();

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return $value;
    }
    public function datetimeInput(string $key, string $label): string
    {
        $value = $this->getValue($key);
        if ($value instanceof \DateTimeInterface) {
            $formattedValue = $value->format('Y-m-d');
        } else {
            $formattedValue = $value; // If it's a string, use it directly
        }
        $inputClass = 'form-control';
        $invalidFeedback = '';
        if (isset($this->errors[$key])) {
            $inputClass .= ' is-invalid';
            $invalidFeedback = '<div class="invalid-feedback">' . implode('<br>', $this->errors[$key]) .'</div>';
        }

        return <<<HTML
        <div class="form-group">
            <label for="field{$key}">{$label}</label>
            <input type="date" id="field{$key}" class="{$inputClass}" name="{$key}" value="{$formattedValue}" required>
            {$invalidFeedback}
        </div>
HTML;
    }
    private function getInputClass (string $key): string
    {
        $inputClass = 'form-control';
        if (isset($this->errors[$key])) {
            $inputClass .= ' is-invalid';
        }
        return $inputClass;
    }
    private function getErrorFeedback (string $key): string
    {
        if (isset($this->errors[$key])) {
            if(is_array($this->errors[$key])) {
                $error = implode('<br>', $this->errors[$key]);
            } else {
                $error = $this->errors[$key];
            }
            return '<div class="invalid-feedback">' . $error . '</div>';
        }
        return '';
    }
    public function select(string $key, string $label, array $options = []): string
    {
        $optionsHTML = [];
        $value = $this->getValue($key);
        foreach ($options as $k => $v) {
            $selected = in_array($k, $value) ? " selected" : "";
            $optionsHTML[] = "<option value=\"$k\"$selected>$v</option>";
        }
        $optionsHTML = implode('', $optionsHTML);
        return <<<HTML
        <div class="form-group">
            <label for="field{$key}">{$label}</label>
            <select id="field{$key}" class="{$this->getInputClass($key)}" name="{$key}[]" required multiple>{$optionsHTML}</select>
            {$this->getErrorFeedback($key)}
        </div>
HTML;
    }

}