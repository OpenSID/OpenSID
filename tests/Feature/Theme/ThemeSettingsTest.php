<?php

namespace Tests\Feature\Theme;

use Tests\TestCase;

class ThemeSettingsTest extends TestCase
{
    /** @test */
    public function test_theme_form_components_render_correctly(): void
    {
        $value = [
            'key' => 'test_key',
            'value' => 'Nilai Default',
            'judul' => 'Judul Uji',
            'type' => 'textarea',
            'keterangan' => 'Keterangan Uji',
            'readonly' => '',
            'default' => 'Nilai Tersimpan',
            'attributes' => 'rows="5"',
            'placeholder' => 'Placeholder Uji',
        ];

        // 1. Test Textarea Component
        $viewTextarea = $this->view('admin.theme.components.form.textarea', ['value' => $value]);
        $viewTextarea->assertSee('Nilai Tersimpan');
        $viewTextarea->assertSee('placeholder="Placeholder Uji"', false);

        // 2. Test Input Text Component
        $viewText = $this->view('admin.theme.components.form.input-text', ['value' => $value]);
        $viewText->assertSee('value="Nilai Tersimpan"', false);

        // 3. Test Input Password Component
        $valuePassword = array_merge($value, ['caption' => 'Caption Password']);
        $viewPassword = $this->view('admin.theme.components.form.input-password', ['value' => $valuePassword]);
        $viewPassword->assertSee('Caption Password');
    }
}
