1. **Update `ProfileController@edit`**:
   - Fetch `purchaseHistory` (sales) for the authenticated user and paginate them. Pass it to the `web.profile` view. Example: `$purchaseHistory = $user->sales()->with('product')->latest()->paginate(10);`
2. **Update `resources/views/web/profile.blade.php`**:
   - Add a new tab link in `.profile-menu` for "Lịch sử mua hàng" (Purchase History).
   - Add a new `.tab-pane` for displaying `purchaseHistory`, similarly formatted to `bookingHistory`.
3. **Check Test cases (`tests/Feature/ProfileTest.php`)**:
   - Update tests to check for "Lịch sử mua hàng" and the new variable.
4. **Pre-commit**: Complete pre-commit steps to ensure proper testing, verification, review, and reflection are done.
5. **Submit**: Submit code.
