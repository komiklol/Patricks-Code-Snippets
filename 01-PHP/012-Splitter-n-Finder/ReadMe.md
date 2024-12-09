# Pin Storage Performance Testing

This is a simple PHP script I came up with while trying to Sleep. The script is designed to Search and find generated PIN codes faster than Traditional searching Methods.

The script allows you to compare different methods for adding and finding PIN codes, with benchmarks and timing measurements. It can easily be executed if you have PHP installed, or alternatively, you can use platforms like [OnlineGDB](https://www.onlinegdb.com/) to run the code without any local setup.

---

## 📖 **About the Project**

The **Pin Storage Performance Testing** script allows you to:

1. Generate random PIN codes of a customizable length.
2. Store the generated PIN codes in different data structures.
3. Perform time-based performance tests to compare the efficiency of each method when adding and searching for PIN codes.

The goal of the project was to test if my idea of a Sorting and Searching algorithm would be faster than the traditional methods.

And it is :D

```
Total Pins  : 50.000
Finding Pin : 596508915635xrdy7880
--------------------------------------------------
for-each   : 0.001661777496
in_array   : 0.001182079315  (in seconds)
My Method  : 0.000425815582
--------------------------------------------------
```

---

## 🖥️ **How to Run the Code**
To change the Amount of PIN Codes generated, you can change the value in line 75.
Pin Codes are generated: 12 chars + 4 numbers + 4 chars. You can Adjust this by changing the values in line 89 at the `pinGenerator()` function.

1. Ensure you have PHP installed on your system.
2. Save the PHP code in a file (e.g., `pinStorageTest.php`).
3. Open a terminal or command prompt, navigate to the folder containing the file, and run the command:
```
php pinStorageTest.php
```
---

## 💡 **Notes**
1. The script uses a memory limit of 4000MB to ensure it can handle large datasets. If your system has limited memory, you may need to adjust this value to avoid memory errors.
2. It compares three different methods for storing and finding PIN codes:
   - Custom method (pinSplitter).
   - "Easy way" with simple array operations (for-each).
   - Another variant of array-based searching (in_array).
