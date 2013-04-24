<?php

class ValidatorTest extends TestCase {

    public function testShouldValidate()
    {
        $validator = new Laramongo\ImageGrabber\Validator;

        $imagePath = app_path().'/tests/assets/image.jpg';

        // With all parameters right
        $params = [
            'width' => 325,
            'height' => 325,
            'weight' => 60,
        ];

        $this->assertTrue(
            $validator->validate($imagePath, $params)
        );
        $this->assertFalse($validator->getLastInvalid());

        // With a smaller weight than the image's
        $params['weight'] = 10;

        $this->assertFalse(
            $validator->validate($imagePath, $params)
        );
        $this->assertContains('out of the permited size', $validator->getLastInvalid());

        // With wrong width
        $params['weight'] = 60;
        $params['width'] = 400;

        $this->assertFalse(
            $validator->validate($imagePath, $params)
        );
        $this->assertContains('out of the permited size', $validator->getLastInvalid());

        $params['width'] = 200;

        $this->assertFalse(
            $validator->validate($imagePath, $params)
        );
        $this->assertContains('out of the permited size', $validator->getLastInvalid());

        // With wrong height
        $params['width'] = 325;
        $params['height'] = 200;

        $this->assertFalse(
            $validator->validate($imagePath, $params)
        );
        $this->assertContains('out of the permited size', $validator->getLastInvalid());

        // With no params/rules at all
        $params = [];

        $this->assertTrue(
            $validator->validate($imagePath, $params)
        );
        $this->assertFalse($validator->getLastInvalid());
    }

    public function testShouldNotValidateNonExistent()
    {
        $validator = new Laramongo\ImageGrabber\Validator;

        $imagePath = 'non/existent/file.jpg';

        $params = [
            'width' => 325,
            'height' => 325,
            'weight' => 60,
        ];

        $this->assertFalse(
            $validator->validate($imagePath, $params)
        );
    }

    public function testShouldNotValidateWithWrongParameters()
    {
        $validator = new Laramongo\ImageGrabber\Validator;

        $imagePath = app_path().'/tests/assets/image.jpg';

        $params = [
            'width' => 325,
            'weight' => 60,
        ];

        $this->assertTrue(
            $validator->validate($imagePath, $params)
        );

        $params = [
            'width' => 'something',
            'lol' => 60,
        ];

        $this->assertFalse(
            $validator->validate($imagePath, $params)
        );
    }
}
