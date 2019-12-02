<?php
namespace App\GraphQL\Mutation\User;
use App\Models\User;
use Auth;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class AssignPinMutation extends Mutation {
	protected $attributes = [
		'name' => 'assignPin',
	];

	public function type(): Type {
		return GraphQL::type('user');
	}

	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
				'rules' => ['required'],
			],
			'pin' => [
				'name' => 'pin',
				'type' => Type::string(),
				'rules' => ['required', 'max:4',
					function ($attribute, $value, $fail) {
						$auth = Auth::user();
						$user = User::find($auth->id);
						if ($user) {
							$tenant_id = 0;
							if (empty($user['tenant'])) {
								$tenant_id = $user['id'];
							} else {
								$tenant_id = $user['tenant'];
							}

							$found = User::where('pin', $value)->where(function ($query) use ($tenant_id) {
								$query->where('tenant', $tenant_id)->orWhere('id', $tenant_id);
							})->count();

							if ($found > 0) {
								$fail('Pin code is taken.');
							}
						} else {
							$fail('Invalid user.');
						}
					}],
			],
		];
	}

	public function validationErrorMessages(array $args = []): array
	{
		return [
			'id.required' => 'User id is required',
		];
	}

	public function resolve($root, $args) {

		$user = User::find($args['id']);

		if (!$user->update($args)) {
			return null;
		}

		return $user;
	}
}