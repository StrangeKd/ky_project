<?php
class Form extends Database
{
    protected $data;
    protected $input;
    public $msg;
    public $error;
    protected $currentDatetime;
    protected $imgUrl;
    protected $defaultClass = [
        'label' => 'log-label',
        'div' => 'log-input-container',
        'input' => 'log-input',
        'btn' => 'log-btn'
    ];
    private const FILE_EXTENSION = ['jpg', 'gif', 'png', 'jpeg'];
    private const FILE_SIZE = 3000000;

    public function __construct(?array $data = null, ?array $input = null)
    {
        $this->input = $input;
        $this->data = $data;
        $this->currentDatetime  = date('Y-m-d H:i:s');
    }

    protected function logMsg(string $message)
    {
        $this->msg = !empty($message) ? $message : null;
        return $this->msg;
    }

    protected function init()
    {
        $this->updateInputStructure();
        $this->setValue();
        $this->checkFile();
    }

    private function updateInputStructure()
    {
        $inputFormated = [];
        foreach ($this->input as $key => $value) {
            $name = $key;
            $type = isset($value['type']) ? $value['type'] : 'text';
            $label = isset($value['label']) ? $value['label'] : ucfirst($name) . ' :';
            $inputFormated[$name] = [
                "type" => $type,
                "name" => $name,
                "label" => $label
            ];
            $inputFormated[$name] = array_merge($inputFormated[$name], $value);
        }
        $this->input = $inputFormated;
    }

    private function setValue()
    {
        foreach ($this->input as $key => $value) {
            if (isset($this->data[$key]) && !empty($this->data[$key])) {
                $this->input[$key]['value'] = htmlentities($this->data[$key]);
            }
        }
    }

    protected function clearData()
    {
        $this->data = [];
    }

    private function addParameters(string $name)
    {
        $keyExclude = ['class', 'labelClass', 'divClass', 'label', 'properties', 'options'];
        $result = '';

        foreach ($this->input[$name] as $key => $value) {
            if (!in_array($key, $keyExclude) && !in_array($value, [false, 'off', ''])) {
                $result .= ' ' . $key . '="' . $value . '"';
            }
        }
        return $result;
    }

    protected function generateForm()
    {
        $form = '';
        foreach ($this->input as $key => $value) {
            $type = $this->input[$key]['type'];
            if ($type === 'select') {
                $form .= $this->select($key);
            } else if ($type === 'checkbox') {
                $form .= $this->checkbox($key);
            } else if ($type === 'textarea') {
                $form .= $this->textarea($key);
            } else if ($type === 'submit') {
                $form .= $this->submit($key);
            } else {
                $form .= $this->input($key);
            }
        }
        echo ($form);
        /* tester return form */
    }

    private function generateInputRequirements(string $name, ?string $class1 = null, ?string $class2 = null) {
        $input = $this->input[$name];
        $label = $this->label($name);
        $properties = isset($input['properties']) ? $input['properties'] : null;
        $divClass = isset($input['divClass']) ? $input['divClass'] : $this->defaultClass[$class1];
        $class = isset($input['class']) ? $input['class'] : $this->defaultClass[$class2];
        $result = [
            'input' => $input,
            'label' => $label,
            'properties' => $properties,
            'divClass' => $divClass,
            'class' => $class
        ];
        return $result;
    }

    private function label(string $name)
    {
        $input = $this->input[$name];
        $class = isset($input['labelClass']) ? $input['labelClass'] : $this->defaultClass['label'];
        $html = '<label for="' . $input['name'] . '" class="' . $class . '">';
        $html .= $input['label'];
        $html .= '</label>';
        return $html;
    }

    private function input(string $name)
    {
        $requirement = $this->generateInputRequirements($name, 'div', 'input');
        $img = // faire une exception pour les input img pour add display flex
        $html = '<div class="' . $requirement['divClass'] . '">' . $requirement['label'];
        $html .= '<input class="' . $requirement['class'] . '" ' . $this->addParameters($name) . $requirement['properties'] . '>';
        $html .= '</div>';
        return $html;
    }

    private function textarea(string $name)
    {
        $requirement = $this->generateInputRequirements($name);
        $html = '<div class="' . $requirement['divClass'] . '">' . $requirement['label'];
        $html .= '<textarea class="' . $requirement['class'] . '" ' . $this->addParameters($name) . $requirement['properties'] . '></textarea>';
        $html .= '</div>';
        return $html;
    }

    private function select(string $name)
    {
        $requirement = $this->generateInputRequirements($name);
        $defaultOption = isset($input['defaultOption']) ? $input['defaultOption'] : 'Select an option';
        $html = '<div class="' . $requirement['divClass'] . '">' . $requirement['label'];
        $html .= '<select class="' . $requirement['class'] . '" ' . $this->addParameters($name) . $requirement['properties'] . '>';
        $html .= '<option value="" disabled selected>' . $defaultOption . '</option>';
        foreach ($requirement['input']['options'] as $key => $value) {
            $html .= '<option value="' . $key . '">' . $value . '</option>';
        }
        $html .= '</select></div>';
        return $html;
    }

    private function checkbox(string $name)
    {
        $requirement = $this->generateInputRequirements($name);
        $html = '<div class="' . $requirement['divClass'] . '">' . $requirement['label'];
        $html .= '<input class="' . $requirement['class'] . '" ' . $this->addParameters($name) . $requirement['properties'] . ">";
        $html .= '</div>';

        return $html;
    }

    private function submit(string $name)
    {
        $input = $this->input[$name];
        $class = isset($input['class']) ? $input['class'] : $this->defaultClass['btn'];
        $html = '<button class="' . $class .'" ' . $this->addParameters($name) . '>' . $input['value'] . '</button>';
        return $html;
    }

    private function hasFileInput()
    {
        foreach ($this->input as $input) {
            if ($input['type'] === 'file') {
                return true;
            }
        }
        return false;
    }

    private function checkFile()
    {
        if ($this->hasFileInput()) {
            foreach ($this->input as $value) {
                if ($value['type'] === 'file' && isset($_FILES[$value['name']])) {
                    if ($_FILES[$value['name']]['error'] !== 4) {
                        foreach ($_FILES as $name => $file) {
                            $fileName = pathinfo($file['name']);
                            $fileExtension = $fileName['extension'];
                            $fileSize = $file['size'];
                            $fileError = $file['error'];
                            if (!in_array($fileExtension, self::FILE_EXTENSION)) {
                                $this->logMsg('File must be in these formats : ' . implode(', ', self::FILE_EXTENSION));
                            } else if ($fileSize >= self::FILE_SIZE || in_array($fileError, [1])) {
                                $this->logMsg('File exceeds ' . (self::FILE_SIZE / 1000000) . 'Mo');
                            } else if (in_array($fileError, [3, 6, 7, 8])) {
                                $this->logMsg('Encountered an error while downloading file');
                            }
                        }
                        if (empty($this->msg)) {
                            $this->uploadFile($name, $file, $fileExtension);
                        }
                    }
                }
            }
        }
    }

    private function uploadFile(string $name, array $file, string $extension)
    {
        if (isset($file)) {
            $imgName = time() . rand() . '.' . $extension;
            $imgUrl = 'asset/upload/' . $imgName;
            move_uploaded_file($file['tmp_name'], $imgUrl);
            $this->input[$name]['value'] = $imgName;
            $this->imgUrl = $imgUrl;
            return true;
        }
    }
}
